<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Channel;

class OldChannelsImporter extends Command
{
    /**
     * Imports Channels from old Lebanese Blogs
     *
     * @var string
     */
    protected $signature = 'channels:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports Channels from old Lebanese Blogs';

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
        $this->comment('Getting Main Channels');
        $oldChannels =  array(
            array(
              'name'        =>  'columnists',
              'description' =>  'Columnists',
              'icon'        =>  'fa-quote-right',
              'color'       =>  '#29639E' // navy
            ),
            array(
              'name'        =>  'design',
              'description' =>  'Marketing & Design',
              'icon'        =>  'fa-picture-o',
              'color'       =>  '#EFC050'
            ),
            array(
              'name'        =>  'fashion',
              'description' =>  'Fashion & Style',
              'icon'        =>  'fa-umbrella',
              'color'       =>  '#C50161'
            ),
            array(
              'name'        =>  'food',
              'description' =>  'Food & Health',
              'icon'        =>  'fa-coffee',
              'color'       =>  '#FF851B'
            ),
            array(
              'name'        =>  'society',
              'description' =>  'Society & Fun',
              'icon'        =>  'fa-smile-o',
              'color'       =>  '#3D9970'
            ),
            array(
              'name'        =>  'politics',
              'description' =>  'Politics & News',
              'icon'        =>  'fa-globe',
              'color'       =>  '#A76336'
            ),
            array(
              'name'        =>  'tech',
              'description' =>  'Tech & Business',
              'icon'        =>  'fa-laptop',
              'color'       =>  '#6C88A0'
            ),
            array(
              'name'        =>  'media',
              'description' =>  'Music, TV & Film',
              'icon'        =>  'fa-music',
              'color'       =>  '#02A7A7'
            )
        );
        
        foreach ($oldChannels as $key => $channel) {
            $this->info('Creating Channel [ ' . $channel['name'] . ' ]' );

            Channel::create([
                'shorthand'    =>   $channel['name'],
                'description'   =>  $channel['description'],
                'color'     => $channel['color']
            ]);
        }

        ////////////////////////////
        //Now Import Sub Channels //
        ////////////////////////////

        $subChannels = array(
            'style'       => 'fashion',
            'health'      => 'food',
            'family'      => 'society',
            'business'    => 'tech',
            'music'       => 'media',
            'tv'          => 'media',
            'film'        => 'media',
            'advertising' => 'design',
            'photography' => 'design',
            'art'         => 'design',
        );

        foreach ($subChannels as $subChannel => $channel) {
            // get id of parent channel;
            $id = Channel::where('shorthand', $channel)->first()->id;

            $this->info('Creating SubChannel [ ' . $subChannel . ' ]' );
            Channel::create([
                'shorthand'     =>   $subChannel,
                'parent_id'     => $id
            ]);
        }
        
    }
}
