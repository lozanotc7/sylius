<?php

namespace ECSPrefix20211002\React\Promise\Timer;

use ECSPrefix20211002\React\EventLoop\Loop;
use ECSPrefix20211002\React\EventLoop\LoopInterface;
use ECSPrefix20211002\React\Promise\CancellablePromiseInterface;
use ECSPrefix20211002\React\Promise\Promise;
use ECSPrefix20211002\React\Promise\PromiseInterface;
function timeout(\ECSPrefix20211002\React\Promise\PromiseInterface $promise, $time, \ECSPrefix20211002\React\EventLoop\LoopInterface $loop = null)
{
    // cancelling this promise will only try to cancel the input promise,
    // thus leaving responsibility to the input promise.
    $canceller = null;
    if ($promise instanceof \ECSPrefix20211002\React\Promise\CancellablePromiseInterface || !\interface_exists('ECSPrefix20211002\\React\\Promise\\CancellablePromiseInterface') && \method_exists($promise, 'cancel')) {
        // pass promise by reference to clean reference after cancellation handler
        // has been invoked once in order to avoid garbage references in call stack.
        $canceller = function () use(&$promise) {
            $promise->cancel();
            $promise = null;
        };
    }
    if ($loop === null) {
        $loop = \ECSPrefix20211002\React\EventLoop\Loop::get();
    }
    return new \ECSPrefix20211002\React\Promise\Promise(function ($resolve, $reject) use($loop, $time, $promise) {
        $timer = null;
        $promise = $promise->then(function ($v) use(&$timer, $loop, $resolve) {
            if ($timer) {
                $loop->cancelTimer($timer);
            }
            $timer = \false;
            $resolve($v);
        }, function ($v) use(&$timer, $loop, $reject) {
            if ($timer) {
                $loop->cancelTimer($timer);
            }
            $timer = \false;
            $reject($v);
        });
        // promise already resolved => no need to start timer
        if ($timer === \false) {
            return;
        }
        // start timeout timer which will cancel the input promise
        $timer = $loop->addTimer($time, function () use($time, &$promise, $reject) {
            $reject(new \ECSPrefix20211002\React\Promise\Timer\TimeoutException($time, 'Timed out after ' . $time . ' seconds'));
            // try to invoke cancellation handler of input promise and then clean
            // reference in order to avoid garbage references in call stack.
            if ($promise instanceof \ECSPrefix20211002\React\Promise\CancellablePromiseInterface || !\interface_exists('ECSPrefix20211002\\React\\Promise\\CancellablePromiseInterface') && \method_exists($promise, 'cancel')) {
                $promise->cancel();
            }
            $promise = null;
        });
    }, $canceller);
}
function resolve($time, \ECSPrefix20211002\React\EventLoop\LoopInterface $loop = null)
{
    if ($loop === null) {
        $loop = \ECSPrefix20211002\React\EventLoop\Loop::get();
    }
    return new \ECSPrefix20211002\React\Promise\Promise(function ($resolve) use($loop, $time, &$timer) {
        // resolve the promise when the timer fires in $time seconds
        $timer = $loop->addTimer($time, function () use($time, $resolve) {
            $resolve($time);
        });
    }, function () use(&$timer, $loop) {
        // cancelling this promise will cancel the timer, clean the reference
        // in order to avoid garbage references in call stack and then reject.
        $loop->cancelTimer($timer);
        $timer = null;
        throw new \RuntimeException('Timer cancelled');
    });
}
function reject($time, \ECSPrefix20211002\React\EventLoop\LoopInterface $loop = null)
{
    return resolve($time, $loop)->then(function ($time) {
        throw new \ECSPrefix20211002\React\Promise\Timer\TimeoutException($time, 'Timer expired after ' . $time . ' seconds');
    });
}
