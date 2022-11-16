<?php

use MOIREI\State\Tests\Database\EnumPostMulticastStatus;
use MOIREI\State\Tests\Database\Post_MulticastEnum;

uses()->group('persistence', 'enum-multicast-state-persistence');

beforeEach(function () {
    include_once __DIR__ . '/../CreatePostsTable.php';
    (new \CreatePostsTable)->up();
});

it('should create model with state', function () {
    /** @var Post_MulticastEnum */
    $post1 = Post_MulticastEnum::factory()->create();
    /** @var Post_MulticastEnum */
    $post2 = Post_MulticastEnum::factory()->state(['published_at' => now()])->create();

    expect($post1->status)->toBeInstanceOf(EnumPostMulticastStatus::class);
    expect($post1->status->is(EnumPostMulticastStatus::CREATED))->toBeTrue();
    expect($post2->status)->toBeInstanceOf(EnumPostMulticastStatus::class);
    expect($post2->status->is(EnumPostMulticastStatus::PUSBLSHED))->toBeTrue();
});

it('should persist enum state set via transitionTo on enum object', function () {
    /** @var Post_MulticastEnum */
    $post = Post_MulticastEnum::factory()->create();

    expect($post->published_at)->toBeNull();

    $post->status->transitionTo(EnumPostMulticastStatus::PUSBLSHED);
    $post->save();

    expect($post->status->is(EnumPostMulticastStatus::PUSBLSHED))->toBeTrue();

    $post = $post->fresh();

    expect($post->published_at)->not->toBeNull();
    expect($post->status->is(EnumPostMulticastStatus::PUSBLSHED))->toBeTrue();
});

it('should persist enum state set via direct assignment on enum object', function () {
    /** @var Post_MulticastEnum */
    $post = Post_MulticastEnum::factory()->create();

    expect($post->published_at)->toBeNull();

    $post->status = EnumPostMulticastStatus::PUSBLSHED;
    $post->save();

    expect($post->status->is(EnumPostMulticastStatus::PUSBLSHED))->toBeTrue();

    $post = $post->fresh();

    expect($post->published_at)->not->toBeNull();
    expect($post->status->is(EnumPostMulticastStatus::PUSBLSHED))->toBeTrue();
});
