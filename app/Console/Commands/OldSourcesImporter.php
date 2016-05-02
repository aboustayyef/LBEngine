<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Source;
use App\Models\Channel;

class OldSourcesImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sources:import {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports Sources from old lb';

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
        // get sources from the old Lebanese blogs

        // check if password is correct;
        if (! \Hash::check($this->argument('password'), '$2y$10$NqTQFNEW8Pl7OVdUOm25Aevmh2.669h0Yc9RYgzpwlZhzW5Fy69oi')) {
            $this->error('Password is not correct');
            return;
        }

        $sources = url_get_contents('http://lebaneseblogs.com/sources/' . $this->argument('password'));
        $sources = json_decode($sources);

        foreach ($sources as $key => $source) {

        $this->info('Adding Source [ ' . $source->blog_name . ' ]');

        // map old columns to new columns
        $newSource = Source::create([
            'shorthand' => $source->blog_id,
            'name' => $source->blog_name,
            'description' => $source->blog_description,
            'homepage' => $source->blog_url,
            'author' => $source->blog_author,
            'author_twitter' => $source->blog_author_twitter_username,
            'rss_feed' => $source->blog_rss_feed,
            'active'    => $source->blog_RSSCrawl_active
        ]);

        // channels
        $oldChannels = preg_split('#\s*,\s*#', $source->blog_tags);
        $newChannels = [];
        
        foreach ($oldChannels as $key => $oldChannel) {
            // If this channel exists, add it.
            if (Channel::where('shorthand',$oldChannel)->get()->count()) {
                $newChannels[] = Channel::where('shorthand',$oldChannel)->first()->id;
            }
        }

        // Now associate source with list of channels;
        $newSource->channels()->sync($newChannels);
        }
        $this->comment('Import complete: Total Sources Added: ' . count($sources));
    }
}
