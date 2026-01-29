<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Rule;
use App\Models\RuleAction;
use Illuminate\Database\Seeder;

class RoomsAndRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Entrance Hall',
                'description' => 'The starting room where the player enters. Connections: Library, Kitchen',
                'rules' => [
                    [
                        'rule_text' => 'Shoes must be placed on the shelf upon entry',
                        'good_actions' => [
                            'Took off sneakers and placed them on the shelf',
                            'Neatly arranged shoes on the shelf',
                            'Placed boots on the shelf near the entrance',
                            'Removed sandals and put them in the designated spot',
                            'Neatly organized footwear on the shelf',
                        ],
                        'violations' => [
                            'Left sneakers in the middle of the floor',
                            'Threw shoes beside the door',
                            'Entered the room with shoes on',
                            'Left footwear on the stairs',
                            'Did not remove shoes upon entry',
                        ],
                    ],
                    [
                        'rule_text' => 'A greeting is mandatory upon first entry',
                        'good_actions' => [
                            'Greeted everyone present upon entry',
                            'Said "Good day" at the entrance',
                            'Politely introduced themselves',
                            'Nodded in greeting',
                            'Shook hands with the host',
                        ],
                        'violations' => [
                            'Entered without greeting',
                            'Ignored those present',
                            'Passed by everyone without a word',
                            'Nodded without verbal communication',
                            'Entered directly without contact',
                        ],
                    ],
                    [
                        'rule_text' => 'Items must not be left on the floor',
                        'good_actions' => [
                            'Hung coat on the coat rack',
                            'Put bag on the table',
                            'Placed backpack on the shelf',
                            'Hung jacket on the coat stand',
                            'Put keys in the dish',
                        ],
                        'violations' => [
                            'Threw bag on the floor',
                            'Left coat on the floor',
                            'Dropped backpack in the middle of the hallway',
                            'Threw jacket beside the door',
                            'Left umbrella on the floor',
                        ],
                    ],
                    [
                        'rule_text' => 'The door must be closed after entry',
                        'good_actions' => [
                            'Closed the front door behind them',
                            'Carefully closed the door',
                            'Checked that the door was closed',
                            'Quietly closed the door',
                            'Locked the door after entry',
                        ],
                        'violations' => [
                            'Left the door open',
                            'Failed to close the door',
                            'Slammed the door',
                            'Left the door ajar',
                            'Did not check if the door was closed',
                        ],
                    ],
                    [
                        'rule_text' => 'Umbrellas must be left in the stand',
                        'good_actions' => [
                            'Put umbrella in the designated stand',
                            'Neatly stored the umbrella',
                            'Placed umbrella near the entrance',
                            'Put wet umbrella in the stand',
                            'Stored umbrella with the others',
                        ],
                        'violations' => [
                            'Threw wet umbrella on the floor',
                            'Left umbrella dripping on the floor',
                            'Left umbrella on the chair',
                            'Leaned umbrella against the wall',
                            'Put umbrella on the windowsill',
                        ],
                    ],
                    [
                        'rule_text' => 'Keys must be left in the dish on the table',
                        'good_actions' => [
                            'Put keys in the dish',
                            'Neatly left keys in the designated spot',
                            'Placed keys in the basket',
                            'Put keys with the others',
                            'Quietly dropped keys in the dish',
                        ],
                        'violations' => [
                            'Left keys in pocket',
                            'Threw keys on the table',
                            'Put keys beside the dish',
                            'Kept keys in hand',
                            'Hid keys in the bag',
                        ],
                    ],
                    [
                        'rule_text' => 'Mail must be left in the designated spot',
                        'good_actions' => [
                            'Put mail in the mail basket',
                            'Stacked letters on the designated shelf',
                            'Placed packages on the mail table',
                            'Neatly arranged delivered items',
                            'Put newspapers on the rack',
                        ],
                        'violations' => [
                            'Threw mail on the floor',
                            'Left letters on the windowsill',
                            'Put packages on the chair',
                            'Scattered mail across the table',
                            'Hid someone else\'s mail',
                        ],
                    ],
                    [
                        'rule_text' => 'The mirror must not be touched with dirty hands',
                        'good_actions' => [
                            'Looked at the mirror without touching',
                            'Kept hands away from the mirror',
                            'Used the mirror without contact',
                            'Wiped hands before approaching the mirror',
                            'Kept distance from the mirror',
                        ],
                        'violations' => [
                            'Touched the mirror with dirty hands',
                            'Left fingerprints on the mirror',
                            'Leaned against the mirror',
                            'Wrote on the mirror',
                            'Smudged the mirror',
                        ],
                    ],
                    [
                        'rule_text' => 'The hallway light must be turned off when leaving',
                        'good_actions' => [
                            'Turned off the light when exiting',
                            'Checked that the light was off',
                            'Flipped the switch off',
                            'Dimmed the light before leaving',
                            'Switched the light to off',
                        ],
                        'violations' => [
                            'Left the light on',
                            'Forgot to turn off the light',
                            'Exited with the light still on',
                            'Did not check the light status',
                            'Ignored the light being on',
                        ],
                    ],
                    [
                        'rule_text' => 'The rug must remain in its place',
                        'good_actions' => [
                            'Straightened the rug after passing',
                            'Returned the rug to its place',
                            'Took care not to move the rug',
                            'Smoothed out the wrinkles in the rug',
                            'Aligned the edges of the rug',
                        ],
                        'violations' => [
                            'Stepped on the rug and bunched it up',
                            'Moved the rug from its place',
                            'Left the rug wrinkled',
                            'Kicked the rug with foot',
                            'Pulled the rug out of position',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Library',
                'description' => 'A place with books and tables. Connections: Entrance Hall, Study',
                'rules' => [
                    [
                        'rule_text' => 'Books must not be removed from shelves without reason',
                        'good_actions' => [
                            'Looked at titles without touching',
                            'Read the book spines',
                            'Browsed the shelves without moving books',
                            'Just looked at the collection',
                            'Kept hands away from the shelves',
                        ],
                        'violations' => [
                            'Removed books from the shelf',
                            'Pulled out books and left them',
                            'Threw books on the table',
                            'Shuffled books on the shelf',
                            'Scattered the books',
                        ],
                    ],
                    [
                        'rule_text' => 'Reading lamp must be used after 6 PM',
                        'good_actions' => [
                            'Turned on the lamp while reading',
                            'Adjusted the lamp to appropriate brightness',
                            'Used the desk lamp',
                            'Activated the table lamp',
                            'Directed the lamp towards self',
                        ],
                        'violations' => [
                            'Read without lamp in the dark',
                            'Used phone for light',
                            'Did not turn on the lamp',
                            'Used overhead ceiling light',
                            'Read in dim light without assistance',
                        ],
                    ],
                    [
                        'rule_text' => 'Chairs must not be moved from their place',
                        'good_actions' => [
                            'Sat in the chair without moving it',
                            'Used the chair in place',
                            'Returned chair if accidentally moved',
                            'Left the chair where it was',
                            'Carefully used the chair',
                        ],
                        'violations' => [
                            'Dragged the chair away from the table',
                            'Moved the chair to the other side',
                            'Pushed the chair with feet',
                            'Left the chair in the middle of the room',
                            'Rearranged the chairs',
                        ],
                    ],
                    [
                        'rule_text' => 'Silence must be maintained in the library',
                        'good_actions' => [
                            'Walked quietly',
                            'Whispered when necessary',
                            'Quietly turned pages',
                            'Moved without making noise',
                            'Kept phone on silent mode',
                        ],
                        'violations' => [
                            'Talked loudly',
                            'Played music',
                            'Jingled keys',
                            'Slammed doors',
                            'Made loud phone calls',
                        ],
                    ],
                    [
                        'rule_text' => 'Books must be returned to the same place',
                        'good_actions' => [
                            'Returned books exactly where they were',
                            'Remembered the spot and returned the book',
                            'Put the book back on the shelf',
                            'Placed the book in the correct position',
                            'Straightened the row of books after returning',
                        ],
                        'violations' => [
                            'Put the book on the wrong shelf',
                            'Left the book on the table',
                            'Returned the book upside down',
                            'Shoved the book without care',
                            'Forgot where the book was',
                        ],
                    ],
                    [
                        'rule_text' => 'Windows must not be opened during winter months',
                        'good_actions' => [
                            'Did not touch the windows',
                            'Left windows closed',
                            'Checked that windows remained closed',
                            'Ignored the windows',
                            'Kept windows closed',
                        ],
                        'violations' => [
                            'Opened the window',
                            'Let in cold air',
                            'Opened window for ventilation',
                            'Left window ajar',
                            'Tried to open the window',
                        ],
                    ],
                    [
                        'rule_text' => 'Notes must not be left on tables',
                        'good_actions' => [
                            'Collected all notes',
                            'Put notes in the drawer',
                            'Cleared papers from the table',
                            'Saved papers in bag',
                            'Cleaned the table of personal items',
                        ],
                        'violations' => [
                            'Left notes on the table',
                            'Scattered papers',
                            'Forgot personal notes',
                            'Wrote on the table',
                            'Tore paper and left it',
                        ],
                    ],
                    [
                        'rule_text' => 'Food and drinks are strictly prohibited',
                        'good_actions' => [
                            'Did not bring food',
                            'Left coffee outside',
                            'Ate before entering the library',
                            'Kept bottle hidden in bag',
                            'Respected the no food rule',
                        ],
                        'violations' => [
                            'Brought coffee into the library',
                            'Ate snacks near the books',
                            'Drank juice',
                            'Left crumbs on the table',
                            'Brought food packaging',
                        ],
                    ],
                    [
                        'rule_text' => 'The book catalog must not be relocated',
                        'good_actions' => [
                            'Browsed catalog without moving it',
                            'Returned catalog to its place',
                            'Left catalog where it was',
                            'Used catalog carefully',
                            'Organized catalog after use',
                        ],
                        'violations' => [
                            'Moved catalog to another table',
                            'Hid the catalog',
                            'Left catalog open',
                            'Wrote in the catalog',
                            'Crumpled catalog pages',
                        ],
                    ],
                    [
                        'rule_text' => 'Curtains must remain drawn during the day',
                        'good_actions' => [
                            'Left curtains as they were',
                            'Did not touch the curtains',
                            'Checked that curtains remained drawn',
                            'Stayed away from the windows',
                            'Respected the curtain position',
                        ],
                        'violations' => [
                            'Lowered the curtains',
                            'Played with the curtains',
                            'Moved curtains aside',
                            'Wrinkled the curtains',
                            'Closed curtains completely',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Kitchen',
                'description' => 'A place where food is prepared. Connections: Entrance Hall, Study',
                'rules' => [
                    [
                        'rule_text' => 'Work surface must be cleaned after use',
                        'good_actions' => [
                            'Wiped the work surface',
                            'Cleaned the counter with a cloth',
                            'Picked up crumbs from the table',
                            'Washed the work surface',
                            'Disinfected the surface after use',
                        ],
                        'violations' => [
                            'Left crumbs on the counter',
                            'Forgot to clean the surface',
                            'Spilled flour and did not clean',
                            'Left sticky residue',
                            'Did not wipe the surface',
                        ],
                    ],
                    [
                        'rule_text' => 'Other people\'s plates must not be touched',
                        'good_actions' => [
                            'Used only own plate',
                            'Took a clean plate from the cabinet',
                            'Asked before using',
                            'Stuck to own belongings',
                            'Respected others\' property',
                        ],
                        'violations' => [
                            'Took someone else\'s plate',
                            'Used someone\'s plate without asking',
                            'Ate from someone else\'s plate',
                            'Moved other people\'s plates',
                            'Hid someone\'s plate',
                        ],
                    ],
                    [
                        'rule_text' => 'Dishes must be returned to their place',
                        'good_actions' => [
                            'Returned plates to the cabinet',
                            'Stacked cups on the shelf',
                            'Put glasses in the designated spot',
                            'Neatly organized dishes',
                            'Arranged dishes by size',
                        ],
                        'violations' => [
                            'Left dishes on the counter',
                            'Threw dishes in the sink',
                            'Put plates on the wrong shelf',
                            'Forgot where the dishes belong',
                            'Left dishes on the table',
                        ],
                    ],
                    [
                        'rule_text' => 'Refrigerator should only be opened when needed',
                        'good_actions' => [
                            'Quickly took what was needed',
                            'Closed refrigerator immediately',
                            'Minimized opening time',
                            'Knew what to take before opening',
                            'Used refrigerator efficiently',
                        ],
                        'violations' => [
                            'Left refrigerator open',
                            'Stared into refrigerator too long',
                            'Opened refrigerator without reason',
                            'Forgot to close the door',
                            'Left refrigerator ajar',
                        ],
                    ],
                    [
                        'rule_text' => 'Knives must be left in the block',
                        'good_actions' => [
                            'Returned knife to the block',
                            'Cleaned knife before returning',
                            'Carefully placed knife in its spot',
                            'Neatly organized the knives',
                            'Put knife in the correct slot',
                        ],
                        'violations' => [
                            'Left knife on the counter',
                            'Threw knife in the drawer',
                            'Put dirty knife in the block',
                            'Left knife in the sink',
                            'Hid knife in the cabinet',
                        ],
                    ],
                    [
                        'rule_text' => 'Stove must be cleaned after cooking',
                        'good_actions' => [
                            'Wiped the stove with a cloth',
                            'Cleaned the burners',
                            'Picked up spilled liquids',
                            'Washed the grates',
                            'Disinfected the stove surface',
                        ],
                        'violations' => [
                            'Left stove dirty',
                            'Forgot to clean the burners',
                            'Left grease on the stove',
                            'Did not wipe spills',
                            'Left burnt residue',
                        ],
                    ],
                    [
                        'rule_text' => 'Trash cans must be closed with the lid',
                        'good_actions' => [
                            'Closed the can with the lid',
                            'Put the lid in place',
                            'Checked that the can was closed',
                            'Carefully lowered the lid',
                            'Properly closed the trash',
                        ],
                        'violations' => [
                            'Left the can open',
                            'Threw the lid aside',
                            'Forgot to close the can',
                            'Left trash visible',
                            'Did not put the lid on',
                        ],
                    ],
                    [
                        'rule_text' => 'Sink must be empty after washing',
                        'good_actions' => [
                            'Removed all dishes from the sink',
                            'Cleaned sink of residue',
                            'Ran water to clean the sink',
                            'Wiped sink with a sponge',
                            'Left sink clean',
                        ],
                        'violations' => [
                            'Left dirty dishes in the sink',
                            'Clogged drain with food',
                            'Left water in the sink',
                            'Forgot to clean residue',
                            'Left sponge in the sink',
                        ],
                    ],
                    [
                        'rule_text' => 'Oven mitts must be returned to the hook',
                        'good_actions' => [
                            'Hung mitts on the hook',
                            'Returned mitts to their place',
                            'Arranged mitts neatly',
                            'Hung mitts near the oven',
                            'Put mitts on the correct hook',
                        ],
                        'violations' => [
                            'Threw mitts on the counter',
                            'Left mitts on the table',
                            'Hid mitts in the drawer',
                            'Forgot where mitts belong',
                            'Threw mitts on the floor',
                        ],
                    ],
                    [
                        'rule_text' => 'Kitchen clock must not be touched',
                        'good_actions' => [
                            'Looked at clock without touching',
                            'Checked time visually',
                            'Kept hands away from the clock',
                            'Used clock only for looking',
                            'Left clock untouched',
                        ],
                        'violations' => [
                            'Moved clock from its place',
                            'Changed time on the clock',
                            'Played with the clock',
                            'Took clock off the wall',
                            'Turned the hands',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Study',
                'description' => 'A place for work and planning. Connections: Library, Kitchen, Garden',
                'rules' => [
                    [
                        'rule_text' => 'Drawers must be closed after use',
                        'good_actions' => [
                            'Closed drawer after taking items',
                            'Checked that drawers were closed',
                            'Pushed drawer back in',
                            'Closed drawers in order',
                            'Made sure drawers were closed',
                        ],
                        'violations' => [
                            'Left drawer open',
                            'Forgot to close drawer',
                            'Left drawer ajar',
                            'Pulled drawer out completely',
                            'Left all drawers open',
                        ],
                    ],
                    [
                        'rule_text' => 'Documents on the desk must not be moved',
                        'good_actions' => [
                            'Looked at documents without touching',
                            'Read only the top document',
                            'Kept hands away from papers',
                            'Left documents as they were',
                            'Did not change paper arrangement',
                        ],
                        'violations' => [
                            'Moved documents',
                            'Scattered papers',
                            'Read private documents',
                            'Rearranged documents on the desk',
                            'Crumpled a document',
                        ],
                    ],
                    [
                        'rule_text' => 'Lights must be turned off when leaving the room',
                        'good_actions' => [
                            'Turned off light on exit',
                            'Flipped the switch',
                            'Checked that light was off',
                            'Turned the button to off',
                            'Pushed switch down',
                        ],
                        'violations' => [
                            'Left light on',
                            'Forgot to turn off light',
                            'Exited with light on',
                            'Did not check the switch',
                            'Ignored the light being on',
                        ],
                    ],
                    [
                        'rule_text' => 'Computer must not be used without permission',
                        'good_actions' => [
                            'Asked for permission',
                            'Did not touch computer',
                            'Left computer untouched',
                            'Stayed away from the keyboard',
                            'Respected computer privacy',
                        ],
                        'violations' => [
                            'Turned on computer without asking',
                            'Typed on the keyboard',
                            'Moved the mouse',
                            'Opened programs',
                            'Browsed files',
                        ],
                    ],
                    [
                        'rule_text' => 'Chair must be returned under the desk',
                        'good_actions' => [
                            'Pushed chair under the desk',
                            'Returned chair to its place',
                            'Positioned chair neatly',
                            'Centered chair under the desk',
                            'Moved chair back',
                        ],
                        'violations' => [
                            'Left chair pushed out',
                            'Pushed chair far from desk',
                            'Forgot to return chair',
                            'Left chair in middle of room',
                            'Moved chair to the corner',
                        ],
                    ],
                    [
                        'rule_text' => 'Phone must be on silent mode',
                        'good_actions' => [
                            'Switched phone to silent',
                            'Turned off phone sound',
                            'Put phone on vibrate',
                            'Turned off ringtones',
                            'Activated silent mode',
                        ],
                        'violations' => [
                            'Left phone with sound on',
                            'Talked loudly on phone',
                            'Played music from phone',
                            'Received calls',
                            'Phone was ringing',
                        ],
                    ],
                    [
                        'rule_text' => 'Books on shelves must remain vertical',
                        'good_actions' => [
                            'Left books upright',
                            'Returned books vertically',
                            'Kept books in upright position',
                            'Aligned books on the shelf',
                            'Organized books neatly',
                        ],
                        'violations' => [
                            'Laid books horizontally',
                            'Stacked books on top of each other',
                            'Moved books diagonally',
                            'Left books randomly',
                            'Piled books without order',
                        ],
                    ],
                    [
                        'rule_text' => 'Window curtain must not be pulled',
                        'good_actions' => [
                            'Left curtain as it was',
                            'Did not touch the window',
                            'Stayed away from the curtain',
                            'Respected curtain position',
                            'Ignored the curtain',
                        ],
                        'violations' => [
                            'Pulled the curtain',
                            'Opened curtain wide',
                            'Played with the curtain',
                            'Moved curtain aside',
                            'Lowered the curtain',
                        ],
                    ],
                    [
                        'rule_text' => 'Writing supplies stay in the jar',
                        'good_actions' => [
                            'Returned pencil to the jar',
                            'Put pen back',
                            'Organized markers in the jar',
                            'Left supplies in the container',
                            'Neatly put everything back',
                        ],
                        'violations' => [
                            'Left pencil on the desk',
                            'Threw pen in the drawer',
                            'Put marker in pocket',
                            'Scattered supplies on the desk',
                            'Hid a pencil',
                        ],
                    ],
                    [
                        'rule_text' => 'Wall calendar must not be changed',
                        'good_actions' => [
                            'Looked at calendar without touching',
                            'Checked date visually',
                            'Kept hands away from calendar',
                            'Left calendar untouched',
                            'Just looked at the date',
                        ],
                        'violations' => [
                            'Changed date on the calendar',
                            'Wrote on the calendar',
                            'Took calendar off the wall',
                            'Turned the pages',
                            'Drew on the calendar',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Garden',
                'description' => 'Outdoor location, bonus room for "critical observation". Connections: Study',
                'rules' => [
                    [
                        'rule_text' => 'Must stay on designated paths',
                        'good_actions' => [
                            'Walked only on the path',
                            'Followed the pathway',
                            'Stayed within boundaries',
                            'Stepped only on permitted areas',
                            'Remained on the concrete path',
                        ],
                        'violations' => [
                            'Walked on the grass',
                            'Trampled flowers',
                            'Crossed over flower beds',
                            'Walked off the path',
                            'Pushed through bushes',
                        ],
                    ],
                    [
                        'rule_text' => 'Flowers must not be picked',
                        'good_actions' => [
                            'Looked at flowers without touching',
                            'Smelled flowers without picking',
                            'Photographed the flowers',
                            'Kept hands away from flowers',
                            'Just observed the flowers',
                        ],
                        'violations' => [
                            'Picked flowers',
                            'Pulled off petals',
                            'Broke flower stems',
                            'Put flowers in pocket',
                            'Snapped the stem',
                        ],
                    ],
                    [
                        'rule_text' => 'Trash must not be thrown in the garden',
                        'good_actions' => [
                            'Put trash in the bin',
                            'Carried trash to the bin',
                            'Picked up trash from the path',
                            'Threw in designated bin',
                            'Held trash until finding a bin',
                        ],
                        'violations' => [
                            'Threw paper on the grass',
                            'Left trash on the path',
                            'Threw bottle in the bushes',
                            'Left bottle on the bench',
                            'Scattered trash',
                        ],
                    ],
                    [
                        'rule_text' => 'Benches must not be moved from their place',
                        'good_actions' => [
                            'Sat on bench where it was',
                            'Used bench without moving it',
                            'Left bench in place',
                            'Sat carefully',
                            'Did not touch the bench',
                        ],
                        'violations' => [
                            'Moved the bench',
                            'Pulled bench from its spot',
                            'Pushed bench with feet',
                            'Changed bench position',
                            'Rotated the bench',
                        ],
                    ],
                    [
                        'rule_text' => 'Garden gate must be locked when leaving',
                        'good_actions' => [
                            'Locked the gate',
                            'Checked the lock',
                            'Inserted key and turned',
                            'Hung key in its place',
                            'Double-locked the gate',
                        ],
                        'violations' => [
                            'Left gate unlocked',
                            'Forgot to lock',
                            'Left key in the lock',
                            'Just pulled gate closed',
                            'Did not check if locked',
                        ],
                    ],
                    [
                        'rule_text' => 'Tools must remain in the shed',
                        'good_actions' => [
                            'Returned tools to the shed',
                            'Hung tools on the hook',
                            'Placed tools on the shelf',
                            'Closed shed behind',
                            'Neatly returned the shovel',
                        ],
                        'violations' => [
                            'Left tools outside',
                            'Threw shovel on the grass',
                            'Forgot tools on the bench',
                            'Left rake on the path',
                            'Hid tools in the bushes',
                        ],
                    ],
                    [
                        'rule_text' => 'Fountain must not be touched',
                        'good_actions' => [
                            'Watched fountain from distance',
                            'Observed water without touching',
                            'Kept hands away from fountain',
                            'Just observed',
                            'Photographed the fountain',
                        ],
                        'violations' => [
                            'Put hands in the water',
                            'Threw coin in the fountain',
                            'Splashed water',
                            'Stirred water with hands',
                            'Moved decorations in fountain',
                        ],
                    ],
                    [
                        'rule_text' => 'Birdhouse must not be disturbed',
                        'good_actions' => [
                            'Observed birds from distance',
                            'Watched birdhouse quietly',
                            'Kept away',
                            'Photographed from afar',
                            'Respected the birds',
                        ],
                        'violations' => [
                            'Knocked on the birdhouse',
                            'Disturbed the birds',
                            'Poked stick in the hole',
                            'Shook the birdhouse',
                            'Yelled at the birds',
                        ],
                    ],
                    [
                        'rule_text' => 'Garden hose must remain coiled',
                        'good_actions' => [
                            'Coiled hose back up',
                            'Returned hose to holder',
                            'Organized hose neatly',
                            'Hung hose on the hook',
                            'Left hose coiled',
                        ],
                        'violations' => [
                            'Left hose stretched out',
                            'Threw hose on the grass',
                            'Forgot to coil hose',
                            'Left hose on the path',
                            'Stepped on the hose',
                        ],
                    ],
                    [
                        'rule_text' => 'Garden figurines must not be moved',
                        'good_actions' => [
                            'Looked at figurines without touching',
                            'Observed the decorations',
                            'Photographed the figurines',
                            'Kept hands away from figurines',
                            'Left figurines in place',
                        ],
                        'violations' => [
                            'Moved the garden gnome',
                            'Rotated a figurine',
                            'Hid a figurine',
                            'Knocked over decorations',
                            'Changed arrangement of figurines',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($rooms as $roomData) {
            $room = Room::create([
                'name' => $roomData['name'],
                'description' => $roomData['description'],
            ]);

            foreach ($roomData['rules'] as $ruleData) {
                $rule = Rule::create([
                    'room_id' => $room->id,
                    'rule_text' => $ruleData['rule_text'],
                ]);

                // Create good actions (is_violation = false)
                foreach ($ruleData['good_actions'] as $actionText) {
                    RuleAction::create([
                        'rule_id' => $rule->id,
                        'action_text' => $actionText,
                        'is_violation' => false,
                    ]);
                }

                // Create violation actions (is_violation = true)
                foreach ($ruleData['violations'] as $actionText) {
                    RuleAction::create([
                        'rule_id' => $rule->id,
                        'action_text' => $actionText,
                        'is_violation' => true,
                    ]);
                }
            }
        }

        $this->command->info('Successfully seeded 5 rooms, 50 rules, and 500 actions!');
    }
}
