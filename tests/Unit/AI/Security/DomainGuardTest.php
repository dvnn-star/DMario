<?php

use App\AI\Security\DomainGuard;
use App\AI\Exceptions\SecurityException;

beforeEach(function () {
    $this->guard = new DomainGuard();
});

test('it allows valid restaurant domain queries', function () {
    $this->guard->check("Berapa omzet hari ini?");
    $this->guard->check("Ada meja yang kosong?");
    $this->guard->check("Tolong update status pesanan 123 jadi completed");
    
    expect(true)->toBeTrue(); // If it didn't throw, it passed
});

test('it blocks out of bounds queries', function () {
    $this->guard->check("I need to learn programming today");
})->throws(SecurityException::class);

test('it blocks political mentions', function () {
    $this->guard->check("What do you think about politics?");
})->throws(SecurityException::class);
