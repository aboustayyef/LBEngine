<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class OldPostsDeleter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:deleteOld';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes post older than 3 weeks. used for cleanup once a day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tenDaysAgo = (new \Carbon\Carbon())->subDays(10);
        $posts = Post::where('publishing_date','<', $tenDaysAgo);
        $t = $this;
        $posts->each(function($post) use ($t){
            $t->comment('Working on post [' .$post->title. ']');
            if ($post->media()->count() > 0) {
                $t->info('post has image');
                if ($post->media->cached == 1) {
                    $t->info('post image has cache');
                    $post->media->deleteCache();
                    $t->info('cache deleted');
                }
                $post->media->delete();
                $t->info('media deleted');
            }
            $post->delete();
            $t->info('Post deleted');
        });
        // find all posts older than 3 weeks
        // for each post
        // if post has media
        //      if media has cache
        //          delete cache
        //      Delete media
        // delete post
    }
}
