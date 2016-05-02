<?php

namespace App\Console\Commands;

use App\Models\Source;
use Illuminate\Console\Command;

class OldSourcesTrimmer extends Command
{
    private $removables;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sources:trim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'trim blogs that are no longer activate or are no longer relevant';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->removables as $key => $removable) {
            $sourceBuilder = Source::where('shorthand', $removable);
            if ($sourceBuilder->count() == 0) { // source not found
                $this->comment('Source: [' . $removable . '] not found');
                continue;
            }
            $source = $sourceBuilder->first();
            $source->active = 0;
            $source->save();
            $this->info('Source: [' . $removable . '] deactivated');
        }
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->removables = ['tech-ticker', 'the-opinion', 'theadsgarage', 'thecubelb', 'thepresentperfect', 'tomybeirut', 'toomextra', 'trella', 'viewoverbeirut', 'wanderingviola', 'whenhopespeaks', 'witchylisa', 'womanunveiled', 'woodenbeirut', 'younglevant', 'ziadmajed', 'shezshe', 'fromagesandfridays', 'brigittekm1', 'koufiya', 'teacalls', 'artofthemideast', 'thefoodblog', 'johnantonios', 'einshtein', 'hopeclinicblog', 'itsdeebatable', 'hamidaouad', 'theminifashion', 'afiftabsh', 'fawdastan', 'nammourette', 'taraghazal', 'missghalayini', 'zahrlaymoun', 'lefthandrednose', 'samarladebeirut', 'salimallawzi', 'umerkabboul', 'donkeshotat', 'mellowshellie', 'smehio', 'joumananammour', 'the50bookproject', 'microsoftoholic', 'helenmackreath', 'beirutandi', 'maatooha', 'piahajal', 'thinkdoms', 'salemmneimneh', 'ghinutrileaks', 'onthesidewalks', 'dropsofcream', 'vaneliassa', 'takesecurityback', 'thebigsam', 'memy50shadesandl', 'menaribo', 'mexicaninbeirut', 'michcafe', 'mindsoupblog', 'missfarah', 'nadinemoawad', 'najissa', 'nakedbana2', 'nourspot', 'nourzahi', 'ohmyhappiness', 'piratebeirut', 'plush-beirut', 'poshlemon', 'qaph', 'rachaelhalabi', 'racing-thoughts', 'ranasalam', 'rationalrepublic', 'rel4tivity', 'saghbini', 'seeqnce', 'sleeplessbeirut', 'smileyface80', 'southoak', 'survivalfirst', 'tajaddod-youth', 'abirghattas', 'alexandra-designs', 'alextohme', 'arabglot', 'arabsaga', 'armigatus', 'husseinitany', 'beirut5ampere', 'beirutiyat', 'bikaffe', 'bilamaliyeh', 'blogtoblague', 'brofessionalreview', 'code4word', 'confettiblues', 'countlesslittlethings', 'dirtykitchensecrets', 'dustywyndow', 'endashemdash', 'ethiopiansuicides', 'figo29', 'gghali', 'ghazayel', 'hahussain', 'homoslibnani', 'hummusnation', 'inkontheside', 'jadaoun', 'jneen8', 'johayna', 'jou3an', 'lamathinks', 'languidlyurged', 'lbcblogs', 'lebanesecomics', 'lebaneseexpatriate', 'lebanonspring', 'leclicblog', 'leelouzworld', 'lifeandstyleandco', 'lifewithsubtitles', 'lobnene', 'loveanon', 'lunasafwan', 'marianachawi', 'marketinginlebanon', 'meandbeirut', 'abirghattas', 'alexandra-designs', 'alextohme', 'arabglot', 'arabsaga', 'armigatus', 'husseinitany', 'beirut5ampere', 'beirutiyat', 'bikaffe', 'bilamaliyeh', 'expatriateinlebanon', 'probeirut', 'nicolashayek', 'evention', 'deliberatedeception', 'sillygoon', 'ultgate', 'mallaidh', 'thewordswehaveforgotten', '105914340896', 'artermisianrumblings', 'lgghanem', 'pearlspowder', 'booksalimallawzi', 'rasharawwas', 'celinek', 'humourkind', 'alishehab', '2famous', 'forgivingbeirut', 'violatre', 'jeremyarbid', 'dreamofchange', 'mariafrangieh', 'chantalsouaid', 'thewiremannequin', 'tomfletcher', 'tabadoul', 'ferrahka', 'mchebaro', 'khaledalameddine', 'eyesonbeirut', 'halaajam', 'absolutegeeks', 'google', 'younglbadult', 'lfadi', 'sharbelfaraj', 'maba3rif', 'shtoshouwhat', 'chitiktikchiti3a', 'toulasblog', 'nokta3alshutter', 'seenye', 'eliamssawir', 'lebmediamonitor', 'taknologia', 'melissatabeek', 'whatrhymeswithellen', 'natalijelrameid', 'tamarindie', 'betket', 'elmerehbi', 'fadykataya', 'toutbeyrouth', 'dyslexialb', 'unelibanaiseaparis', 'behindthef', 'abedkataya', 'kamyoon', 'vwsquarebacktolife', 'dinahamzeh', 'atouchofdesign', 'eastreport', 'gajreige', 'tfour', 'allobeirut', 'pamghanem', 'modeaupoint', 'lejournaldeleen', 'farewellchronicles', 'ibosblog', 'streetfoodgalore', 'thewardrobesoldier', 'innerbeautybymariam', 'heelsoverheads', 'lebanesefashionistanyc', 'beirutcityguide', 'socialmediatag', 'mayasingredients', 'cloud961', 'acharif', 'moophz', 'thepurpleroseofbeirut', 'w0rd', 'rawanekhalil', 'breakingforever', 'techgeek365', 'jocelynedebs', 'bitoria', '3a-rawa2', 'waelkanaan', 'tantekawkab', 'roadtriphacks', 'garlikandsapphires', 'noureddine', 'nounzilicious', 'slowding', 'adrianaleboss', 'nkoteich-almodon', 'zmakhoulolj', 'beyondbeirut', 'mycharmedplaces', 'khbar', 'neweasternpolitics', 'efficientelephant', '3almeshe', 'theribz', 'fekratmojtama3', 'bananarepublic1920', 'winedipity', 'foodnloubs', 'bettydedeian', 'beirutsbrightside', 'normamakarem', 'hayataoun', 'frederictech', 'nmaer', 'keepinitpheel', 'joura-comic', 'arsmex', 'lebanonads', 'legymonline', 'humansoflebanon', 'notesofatraveler', 'lubnani', 'liveandcoexist', 'medicaholic', 'wheninbeirut', 'speshelb', 'cinetrotter', 'stupidtoast', 'zeinaelhoss', 'eliewehbe', 'maya-yared', 'lebanonuntravelled', 'lebreviewblog', 'jasoninbeirut', 'fashionetpassion', 'wedleb', 'mustafaris', 'faressaad', 'beirutisalive', 'nfornour','zinga', 'espressopatronum'];
    }

}