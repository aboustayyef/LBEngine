<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NewsSourcesAdder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:addNews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds "News" as a new channel. Then adds The Daily Star and Naharnet as news sources';

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
        // create news Channel
        
        $this->info('Creating the News Chanel');

        $news = \App\Channel::create([
            'shorthand'     =>  'news',
            'description'   =>  'News',
            'color'         =>  '#555555',
            'parent_id'     =>  null,
       ]);

        $politics = \App\Channel::where('shorthand' , 'politics')->first();

        $this->info('Creating The Daily Star Source');

        $ds = \App\Source::create([
            'shorthand'     =>  'dailystar',
            'name'          =>  'The Daily Star',
            'description'   =>  'Lebanese Leading English Language Daily',
            'homepage'      =>  'http://www.dailystar.com.lb/News/Lebanon-News.ashx',
            'rss_feed'      =>  null,
            'active'        =>  1,
            'author_twitter'    =>  'DailyStarLeb',
            
        ]);

        $this->info('Creating the Naharnet Source');

        $naharnet = \App\Source::create([
            'shorthand'     =>  'naharnet',
            'name'          =>  'Naharnet',
            'description'   =>  'Naharnet',
            'homepage'      =>  'http://www.naharnet.com/lebanon',
            'rss_feed'      =>  null,
            'active'        =>  1,
            'author_twitter'    =>  'Naharnet',
        ]);

        $this->info('Attaching News and Politics Channels to the Daily Star');
        $ds->channels()->sync([$news->id, $politics->id]);

        $this->info('Attaching News and Politics Channels to Naharnet');
        $naharnet->channels()->sync([$news->id, $politics->id]);

    }
}
