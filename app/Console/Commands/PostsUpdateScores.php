<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\FbClient;


class PostsUpdateScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:updateScores {gravity=0.90}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Scores of posts for ranking';

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
        $threeDaysAgo = (new \Carbon\Carbon())->subDays(3);
        $posts = Post::all();
        $fb = new FbClient();
        $t = $this;
        $posts->each(function($post) use ($fb, $t){
            $post->update_shares($fb);
            $post->update_scores($this->argument('gravity'));
            $post->save();
            $t->info('['.$post->title.'] Shares: ' . $post->shares . ' , Score: ' . $post->score);
        });
    }
}
