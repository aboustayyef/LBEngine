<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Source;
use App\Models\Post;

class PostsGetter extends Command
{
    protected $shorthand, $startTime;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:get {source_shorthand?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gets latest posts and saves to database if dont exist';

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


    public function beginning_stamp()
    {
        $this->info('========================================');
        $this->startTime = new \Carbon\Carbon();
        $this->info('Work began: '. $this->startTime );
        $robot = shell_exec('whoami');
        $whichphp = shell_exec('which php');
        $this->info("Robot: $robot");
        $this->info("PHP in use: $whichphp");
        $this->info('========================================');
    }

    public function end_stamp()
    {
        $end = new \Carbon\Carbon();
        $this->comment('Work ended ' . $end . ' [' . $end->diffForHumans($this->startTime) . ' starting ] ');
    }

    public function handle()
    {
        $this->shorthand = $this->argument('source_shorthand');

        // if an argument is passed
        if ($this->shorthand) 
        {
            // if a source with passed shorthand exists
            if (Source::Where('shorthand', $this->shorthand)->count() > 0 ) 
            {
                $source = Source::where('shorthand', $this->shorthand)->first();
                $this->beginning_stamp();
                $this->getPostsFromSource($source);
                $this->end_stamp();

            }
            else
            {
                $this->error('There is no blog with the shorthand "' . $this->shorthand . '"');
            }
        } 
        else 
        {
            // no source precised, get from all active sources;
            $this->beginning_stamp();
            $sources = Source::Where('active', 1)->get();
            foreach ($sources as $key => $source) {
                try {
                    $this->comment('Getting Posts from [' .$source->name. ']');
                    $this->getPostsFromSource($source);
                } catch (\Exception $e) {
                    $this->error('Trouble with source [' . $source->shorthand . ']');
                    continue;
                }
            }
            $this->end_stamp();
        }
    }
    public function getPostsFromSource(Source $source)
    {
        // for each source, crawl the list of the latest posts
        $posts = $source->crawlLatestPosts();
        
        $posts->each(function($post, $key)
        {
            // if post doesnt exist, save;
            if (Post::urlExists($post->url)) {
                $this->info('Post [' . $post->title . '] already exists');
                return;
            }

            if (isset($post->publishing_date)) {
                $postAgeInDays = (new \Carbon\Carbon($post->publishing_date))->diffInDays();
                if ($postAgeInDays > 10) {
                    // discard because post is older than 3 days
                    $this->info('Post is older than 10 days, ignoring');
                    return;
                }
            }


            $post = $post->build(); // get details from post;
            $post->save();
            $post->channels()->sync($post->source->channels);
            $this->info('Post Saved, fetching image');
            $post->getImage(); 
            if ($post->media()->count() > 0) { // post has image
                $post->media->createCache();
            }
            // $post->getRating();
            
            $this->info('Post [' . $post->title . '] saved');
            $this->info('Assigning Channels');
            $post->channels()->sync($post->source->channels);
        });

    }
}