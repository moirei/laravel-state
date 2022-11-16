<?php

use MOIREI\State\Tests\Database\ClassPostMulticastStatus;
use MOIREI\State\Tests\Database\Post_MulticastClass;

uses()->group('persistence', 'class-multicast-state-persistence');

beforeEach(function () {
    include_once __DIR__ . '/../CreatePostsTable.php';
    (new \CreatePostsTable)->up();
});

it('should create model with state', function () {
    /** @var Post_MulticastClass */
    $post1 = Post_MulticastClass::factory()->create();
    /** @var Post_MulticastClass */
    $post2 = Post_MulticastClass::factory()->state(['published_at' => now()])->create();

    expect($post1->status)->toBeInstanceOf(ClassPostMulticastStatus::class);
    expect($post1->status->is(ClassPostMulticastStatus::CREATED))->toBeTrue();
    expect($post2->status)->toBeInstanceOf(ClassPostMulticastStatus::class);
    expect($post2->status->is(ClassPostMulticastStatus::PUSBLSHED))->toBeTrue();
});

it('should persist class state set via transitionTo on class object', function () {
    /** @var Post_MulticastClass */
    $post = Post_MulticastClass::factory()->create();

    expect($post->published_at)->toBeNull();

    $post->status->transitionTo(ClassPostMulticastStatus::PUSBLSHED);
    $post->save();

    expect($post->status->is(ClassPostMulticastStatus::PUSBLSHED))->toBeTrue();

    $post = $post->fresh();

    expect($post->published_at)->not->toBeNull();
    expect($post->status->is(ClassPostMulticastStatus::PUSBLSHED))->toBeTrue();
});

it('should persist class state set via direct assignment on class object', function () {
    /** @var Post_MulticastClass */
    $post = Post_MulticastClass::factory()->create();

    expect($post->published_at)->toBeNull();

    $post->status = ClassPostMulticastStatus::PUSBLSHED;
    $post->save();

    expect($post->status->is(ClassPostMulticastStatus::PUSBLSHED))->toBeTrue();

    $post = $post->fresh();

    expect($post->published_at)->not->toBeNull();
    expect($post->status->is(ClassPostMulticastStatus::PUSBLSHED))->toBeTrue();
});
