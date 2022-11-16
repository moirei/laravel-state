<?php

use MOIREI\State\Tests\Database\EnumPostStatus;
use MOIREI\State\Tests\Database\Post_CastedEnum;

uses()->group('persistence', 'class-state-persistence');

beforeEach(function () {
    include_once __DIR__.'/../CreatePostsTable.php';
    (new \CreatePostsTable)->up();
});

it('should create model with state', function () {
    /** @var Post_CastedEnum */
    $post1 = Post_CastedEnum::factory()->create();
    /** @var Post_CastedEnum */
    $post2 = Post_CastedEnum::factory()->state(['status' => EnumPostStatus::PUSBLSHED->value])->create();

    expect($post1->status)->toBeInstanceOf(EnumPostStatus::class);
    expect($post1->status->is(EnumPostStatus::CREATED))->toBeTrue();
    expect($post2->status)->toBeInstanceOf(EnumPostStatus::class);
    expect($post2->status->is(EnumPostStatus::PUSBLSHED))->toBeTrue();
});

it('should persist enum state set via transitionTo on enum object', function () {
    /** @var Post_CastedEnum */
    $post = Post_CastedEnum::factory()->create();

    $post->status->transitionTo(EnumPostStatus::PUSBLSHED);
    $post->save();

    expect($post->status->is(EnumPostStatus::PUSBLSHED))->toBeTrue();

    $post = $post->fresh();

    expect($post->status->is(EnumPostStatus::PUSBLSHED))->toBeTrue();
});

it('should persist enum state set via direct assignment on enum object', function () {
    /** @var Post_CastedEnum */
    $post = Post_CastedEnum::factory()->create();

    $post->status = EnumPostStatus::PUSBLSHED;
    $post->save();

    expect($post->status->is(EnumPostStatus::PUSBLSHED))->toBeTrue();

    $post = $post->fresh();

    expect($post->status->is(EnumPostStatus::PUSBLSHED))->toBeTrue();
});
