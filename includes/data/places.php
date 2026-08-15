<?php

defined('APP_ROOT') || exit('Direct access is not permitted.');
/**
 * Travel destinations, mountains, rivers, roads, transport and geography.
 * Every entry carries lat/lon so it can be pinned on the interactive map.
 */

return [
    'geography' => [
        'continent'        => 'Asia',
        'subregion'        => 'South Asia (Indian subcontinent)',
        'tectonic_plate'   => 'Indian Plate',
        'delta'            => 'Ganges–Brahmaputra–Meghna Delta — the largest river delta on Earth',
        'area_km2'         => 148460,
        'land_km2'         => 130170,
        'water_km2'        => 18290,
        'coastline_km'     => 580,
        'land_border_km'   => 4413,
        'borders'          => ['India' => 4156, 'Myanmar' => 271],
        'sea'              => 'Bay of Bengal',
        'highest_point'    => ['name' => 'Saka Haphong (Mowdok Mual)', 'height_m' => 1052],
        'lowest_point'     => ['name' => 'Bay of Bengal coast', 'height_m' => 0],
        'mean_elevation_m' => 85,
        'rivers_count'     => 907,
        'timezone'         => 'BST — Bangladesh Standard Time (UTC+06:00), no daylight saving',
        'extremes'         => [
            'North' => ['name' => 'Banglabandha, Panchagarh',  'lat' => 26.6383, 'lon' => 88.3833],
            'South' => ['name' => "St. Martin's Island (Chera Dwip)", 'lat' => 20.5760, 'lon' => 92.3230],
            'East'  => ['name' => 'Akhinathang, Bandarban',     'lat' => 21.9530, 'lon' => 92.6740],
            'West'  => ['name' => 'Monakosha, Chapainawabganj', 'lat' => 24.2530, 'lon' => 88.0084],
        ],
        'climate' => [
            ['season' => 'Grishmo (Summer)',   'bn' => 'গ্রীষ্ম',   'months' => 'Apr – May',   'temp' => '28–40°C', 'note' => 'Hot and humid, occasional nor’westers (kalbaishakhi)'],
            ['season' => 'Barsha (Monsoon)',   'bn' => 'বর্ষা',     'months' => 'Jun – Aug',   'temp' => '25–32°C', 'note' => 'Around 80% of annual rainfall; rivers swell'],
            ['season' => 'Sharat (Autumn)',    'bn' => 'শরৎ',      'months' => 'Sep – Oct',   'temp' => '24–32°C', 'note' => 'Kash flowers, blue skies, festival season'],
            ['season' => 'Hemanta (Late autumn)','bn' => 'হেমন্ত', 'months' => 'Oct – Nov',   'temp' => '20–29°C', 'note' => 'Harvest, nabanna celebrations'],
            ['season' => 'Sheet (Winter)',     'bn' => 'শীত',      'months' => 'Dec – Jan',   'temp' => '9–24°C',  'note' => 'Dry, foggy mornings, date molasses and pitha'],
            ['season' => 'Boshonto (Spring)',  'bn' => 'বসন্ত',    'months' => 'Feb – Mar',   'temp' => '18–30°C', 'note' => 'Pohela Falgun, flowering trees'],
        ],
    ],

    // ------------------------------------------------------------ mountains
    'mountains' => [
        ['name' => 'Saka Haphong', 'alt' => 'Mowdok Mual / Tlangmoy', 'height' => 1052, 'district' => 'Bandarban', 'range' => 'Chittagong Hill Tracts', 'lat' => 21.7869, 'lon' => 92.6081, 'note' => 'Undisputed highest peak of Bangladesh, near the Myanmar border.'],
        ['name' => 'Zow Tlang',    'alt' => 'Jo Tlang',              'height' => 1005, 'district' => 'Bandarban', 'range' => 'Chittagong Hill Tracts', 'lat' => 21.7960, 'lon' => 92.5900, 'note' => 'Second highest; ridge walk from Remakri Prangsa.'],
        ['name' => 'Dumlong',      'alt' => 'Rangtlang',             'height' => 998,  'district' => 'Rangamati', 'range' => 'Chittagong Hill Tracts', 'lat' => 22.1500, 'lon' => 92.5400, 'note' => 'Remote peak in Bilaichari, rarely summited.'],
        ['name' => 'Keokradong',   'alt' => 'Keokradong',            'height' => 986,  'district' => 'Bandarban', 'range' => 'Chittagong Hill Tracts', 'lat' => 21.8600, 'lon' => 92.5150, 'note' => 'The most famous trekking peak; long thought the highest.'],
        ['name' => 'Tazing Dong',  'alt' => 'Bijoy',                 'height' => 970,  'district' => 'Bandarban', 'range' => 'Chittagong Hill Tracts', 'lat' => 21.8300, 'lon' => 92.5000, 'note' => 'Officially declared the highest peak for many years.'],
        ['name' => 'Mowdok Taung', 'alt' => '',                      'height' => 1003, 'district' => 'Bandarban', 'range' => 'Mowdok Range',           'lat' => 21.7700, 'lon' => 92.6200, 'note' => 'Part of the Mowdok range straddling the border ridge.'],
        ['name' => 'Chimbuk',      'alt' => 'Chimbuk Pahar',         'height' => 762,  'district' => 'Bandarban', 'range' => 'Chittagong Hill Tracts', 'lat' => 21.9330, 'lon' => 92.3170, 'note' => 'Roadside viewpoint — "the roof of Bangladesh" for road travellers.'],
        ['name' => 'Nilgiri',      'alt' => 'Nil Giri',              'height' => 701,  'district' => 'Bandarban', 'range' => 'Chittagong Hill Tracts', 'lat' => 21.9560, 'lon' => 92.3020, 'note' => 'Resort above the clouds; sunrise over a sea of fog.'],
        ['name' => 'Sajek Ridge',  'alt' => 'Konglak Para',          'height' => 550,  'district' => 'Rangamati', 'range' => 'Sajek Valley',           'lat' => 23.3833, 'lon' => 92.2917, 'note' => 'Highest motorable valley resort area of the country.'],
        ['name' => 'Chandranath Hill','alt' => 'Sitakunda',          'height' => 350,  'district' => 'Chattogram','range' => 'Sitakunda Range',        'lat' => 22.6200, 'lon' => 91.6600, 'note' => 'Sacred Shakti Peetha temple hill with a famous stair climb.'],
        ['name' => 'Garo Hills edge','alt' => 'Birishiri',           'height' => 300,  'district' => 'Netrokona', 'range' => 'Garo Hills foothills',   'lat' => 25.1200, 'lon' => 90.6300, 'note' => 'China-clay hills and turquoise lakes on the Meghalaya frontier.'],
    ],

    // ---------------------------------------------------------- destinations
    'travel' => [
        ['name' => 'Cox’s Bazar Sea Beach', 'district' => "Cox's Bazar", 'type' => 'Beach', 'lat' => 21.4272, 'lon' => 92.0058, 'best' => 'Nov – Mar', 'emoji' => '🏖️', 'desc' => 'The longest uninterrupted natural sea beach in the world, running about 120 km from Cox’s Bazar to Teknaf.'],
        ['name' => 'The Sundarbans', 'district' => 'Khulna', 'type' => 'Forest', 'lat' => 21.9497, 'lon' => 89.1833, 'best' => 'Dec – Feb', 'emoji' => '🐅', 'desc' => 'Largest mangrove forest on Earth and a UNESCO World Heritage Site — home of the Royal Bengal Tiger.'],
        ['name' => 'Sajek Valley', 'district' => 'Rangamati', 'type' => 'Hills', 'lat' => 23.3833, 'lon' => 92.2917, 'best' => 'Jul – Oct', 'emoji' => '⛰️', 'desc' => 'The "queen of hills" — clouds roll through the resort huts at dawn, 1,800 ft above sea level.'],
        ['name' => 'Sreemangal Tea Gardens', 'district' => 'Moulvibazar', 'type' => 'Nature', 'lat' => 24.3065, 'lon' => 91.7296, 'best' => 'Oct – Mar', 'emoji' => '🍃', 'desc' => 'Tea capital of Bangladesh — endless green terraces, the seven-layer tea and Lawachara rainforest.'],
        ['name' => "St. Martin's Island", 'district' => "Cox's Bazar", 'type' => 'Island', 'lat' => 20.6270, 'lon' => 92.3230, 'best' => 'Nov – Feb', 'emoji' => '🏝️', 'desc' => 'The only coral island of Bangladesh, locally called Narikel Jinjira — coconut, coral and turquoise water.'],
        ['name' => 'Kuakata Sea Beach', 'district' => 'Patuakhali', 'type' => 'Beach', 'lat' => 21.8167, 'lon' => 90.1200, 'best' => 'Oct – Mar', 'emoji' => '🌅', 'desc' => 'The "daughter of the sea" — one of very few beaches where both sunrise and sunset fall over the water.'],
        ['name' => 'Ratargul Swamp Forest', 'district' => 'Sylhet', 'type' => 'Forest', 'lat' => 25.0067, 'lon' => 91.9522, 'best' => 'Jun – Sep', 'emoji' => '🛶', 'desc' => 'The only freshwater swamp forest of the country — paddle a boat between submerged tree trunks.'],
        ['name' => 'Tanguar Haor', 'district' => 'Sunamganj', 'type' => 'Wetland', 'lat' => 25.1167, 'lon' => 91.1000, 'best' => 'Jun – Sep', 'emoji' => '🦆', 'desc' => 'A Ramsar wetland of 100+ interconnected beels hosting tens of thousands of migratory birds each winter.'],
        ['name' => 'Somapura Mahavihara, Paharpur', 'district' => 'Naogaon', 'type' => 'Heritage', 'lat' => 25.0311, 'lon' => 88.9772, 'best' => 'Nov – Mar', 'emoji' => '🛕', 'desc' => 'UNESCO site — an 8th-century Buddhist monastery, once the largest south of the Himalayas.'],
        ['name' => 'Sixty Dome Mosque (Shat Gambuj)', 'district' => 'Bagerhat', 'type' => 'Heritage', 'lat' => 22.6742, 'lon' => 89.7411, 'best' => 'Nov – Mar', 'emoji' => '🕌', 'desc' => 'UNESCO site built by Khan Jahan Ali in the 15th century — 60 pillars, 77 domes, unmatched brickwork.'],
        ['name' => 'Mahasthangarh', 'district' => 'Bogura', 'type' => 'Heritage', 'lat' => 24.9667, 'lon' => 89.3417, 'best' => 'Nov – Mar', 'emoji' => '🏺', 'desc' => 'The oldest urban archaeological site of Bangladesh — the ancient city of Pundranagara, 3rd century BCE.'],
        ['name' => 'Lalbagh Fort', 'district' => 'Dhaka', 'type' => 'Heritage', 'lat' => 23.7188, 'lon' => 90.3880, 'best' => 'All year', 'emoji' => '🏰', 'desc' => 'An unfinished 17th-century Mughal fort complex with the tomb of Pari Bibi at its heart.'],
        ['name' => 'Ahsan Manzil', 'district' => 'Dhaka', 'type' => 'Heritage', 'lat' => 23.7086, 'lon' => 90.4058, 'best' => 'All year', 'emoji' => '🏛️', 'desc' => 'The pink palace on the Buriganga — residence of the Nawabs of Dhaka, now a museum.'],
        ['name' => 'Jatiya Sangsad Bhaban', 'district' => 'Dhaka', 'type' => 'Architecture', 'lat' => 23.7625, 'lon' => 90.3784, 'best' => 'All year', 'emoji' => '🏢', 'desc' => 'Louis Kahn’s National Parliament House — one of the largest and most admired legislative complexes in the world.'],
        ['name' => 'Kantajew Temple', 'district' => 'Dinajpur', 'type' => 'Heritage', 'lat' => 25.6836, 'lon' => 88.6656, 'best' => 'Nov – Mar', 'emoji' => '🛕', 'desc' => 'An 18th-century terracotta Hindu temple covered with more than 15,000 sculpted plaques.'],
        ['name' => 'Nilgiri & Chimbuk', 'district' => 'Bandarban', 'type' => 'Hills', 'lat' => 21.9560, 'lon' => 92.3020, 'best' => 'Jul – Feb', 'emoji' => '☁️', 'desc' => 'Resorts perched above the cloudline where the fog sits below your balcony at sunrise.'],
        ['name' => 'Nafakhum Waterfall', 'district' => 'Bandarban', 'type' => 'Waterfall', 'lat' => 21.8667, 'lon' => 92.5833, 'best' => 'Aug – Oct', 'emoji' => '💦', 'desc' => 'The largest waterfall by volume in Bangladesh, reached by boat and trek along the Sangu river.'],
        ['name' => 'Jaflong & Bichanakandi', 'district' => 'Sylhet', 'type' => 'Nature', 'lat' => 25.1667, 'lon' => 92.0167, 'best' => 'Jun – Oct', 'emoji' => '🪨', 'desc' => 'Crystal streams full of rolling stones with the Meghalaya hills and waterfalls as a backdrop.'],
        ['name' => 'Kaptai Lake', 'district' => 'Rangamati', 'type' => 'Lake', 'lat' => 22.4956, 'lon' => 92.2200, 'best' => 'Oct – Mar', 'emoji' => '🚤', 'desc' => 'The largest man-made lake of the country, dotted with islands, hanging bridges and hill villages.'],
        ['name' => 'Nijhum Dwip', 'district' => 'Noakhali', 'type' => 'Island', 'lat' => 22.0500, 'lon' => 91.0333, 'best' => 'Nov – Feb', 'emoji' => '🦌', 'desc' => 'A quiet island national park where herds of spotted deer roam the keora forest at dusk.'],
        ['name' => 'Panam City', 'district' => 'Narayanganj', 'type' => 'Heritage', 'lat' => 23.6450, 'lon' => 90.5980, 'best' => 'Nov – Mar', 'emoji' => '🏚️', 'desc' => 'The abandoned merchant street of Sonargaon — a lost city of colonial-era mansions.'],
        ['name' => 'Mahamaya & Foy’s Lake', 'district' => 'Chattogram', 'type' => 'Lake', 'lat' => 22.3667, 'lon' => 91.7900, 'best' => 'Oct – Mar', 'emoji' => '🛶', 'desc' => 'Kayak between forested hills at Mahamaya and ride the theme park at Foy’s Lake.'],
        ['name' => 'Birishiri & Somesshwari', 'district' => 'Netrokona', 'type' => 'Nature', 'lat' => 25.1200, 'lon' => 90.6300, 'best' => 'Oct – Mar', 'emoji' => '💠', 'desc' => 'Blue-water china-clay lakes, white sand riverbeds and Garo hill culture.'],
        ['name' => 'National Martyrs’ Memorial, Savar', 'district' => 'Dhaka', 'type' => 'Monument', 'lat' => 23.9086, 'lon' => 90.2549, 'best' => 'All year', 'emoji' => '🕯️', 'desc' => 'Jatiyo Smriti Soudho — seven soaring planes honouring the martyrs of the 1971 Liberation War.'],
    ],

    // --------------------------------------------------------------- rivers
    'rivers' => [
        ['name' => 'Padma',        'bn' => 'পদ্মা',      'length' => 341,  'note' => 'The main distributary of the Ganges inside Bangladesh.'],
        ['name' => 'Jamuna',       'bn' => 'যমুনা',      'length' => 205,  'note' => 'The Brahmaputra’s main channel — among the widest rivers on Earth.'],
        ['name' => 'Meghna',       'bn' => 'মেঘনা',      'length' => 264,  'note' => 'Carries the combined delta flow to the Bay of Bengal.'],
        ['name' => 'Brahmaputra',  'bn' => 'ব্রহ্মপুত্র', 'length' => 276,  'note' => 'Old Brahmaputra course through Mymensingh.'],
        ['name' => 'Surma',        'bn' => 'সুরমা',      'length' => 249,  'note' => 'Main river of the Sylhet basin.'],
        ['name' => 'Kushiyara',    'bn' => 'কুশিয়ারা',   'length' => 228,  'note' => 'Twin of the Surma, fed by the Barak.'],
        ['name' => 'Karnaphuli',   'bn' => 'কর্ণফুলী',   'length' => 180,  'note' => 'Powers Kaptai dam and Chattogram port; Bangabandhu Tunnel runs beneath it.'],
        ['name' => 'Teesta',       'bn' => 'তিস্তা',     'length' => 115,  'note' => 'Lifeline of the north, shared with India.'],
        ['name' => 'Sangu',        'bn' => 'সাঙ্গু',     'length' => 173,  'note' => 'The only major river rising inside Bangladesh.'],
        ['name' => 'Buriganga',    'bn' => 'বুড়িগঙ্গা',  'length' => 27,   'note' => 'The river Dhaka grew up on.'],
        ['name' => 'Rupsha',       'bn' => 'রূপসা',      'length' => 32,   'note' => 'Khulna’s port river.'],
        ['name' => 'Halda',        'bn' => 'হালদা',      'length' => 81,   'note' => 'The only natural carp breeding river in South Asia.'],
    ],

    // ---------------------------------------------------------------- roads
    'roads' => [
        ['code' => 'N1', 'name' => 'Dhaka – Chattogram – Teknaf Highway', 'length' => 459, 'note' => 'The busiest trade corridor of the country; part of Asian Highway AH41.'],
        ['code' => 'N2', 'name' => 'Dhaka – Sylhet Highway', 'length' => 291, 'note' => 'Gateway to the tea belt and the Tamabil land port.'],
        ['code' => 'N3', 'name' => 'Dhaka – Mymensingh Highway', 'length' => 87, 'note' => 'Four-lane corridor through Gazipur’s industrial belt.'],
        ['code' => 'N4', 'name' => 'Dhaka – Tangail (Jamuna Bridge link)', 'length' => 98, 'note' => 'Feeds the Bangabandhu Bridge to the north-west.'],
        ['code' => 'N5', 'name' => 'Dhaka – Rangpur – Banglabandha Highway', 'length' => 493, 'note' => 'The northern spine, ending at the Nepal-facing land port.'],
        ['code' => 'N6', 'name' => 'Hatikumrul – Rajshahi Highway', 'length' => 154, 'note' => 'Main access to the Rajshahi division.'],
        ['code' => 'N7', 'name' => 'Daulatdia – Khulna – Mongla Highway', 'length' => 275, 'note' => 'Connects the south-west and Mongla seaport.'],
        ['code' => 'N8', 'name' => 'Dhaka – Barishal – Kuakata (Padma Bridge corridor)', 'length' => 292, 'note' => 'Transformed by the Padma Bridge — Dhaka to Barishal in about 3 hours.'],
        ['code' => 'N102','name' => 'Dhaka – Aricha Highway', 'length' => 78, 'note' => 'Historic western exit from the capital.'],
        ['code' => 'AH1', 'name' => 'Asian Highway 1 (Benapole – Dhaka – Tamabil)', 'length' => 492, 'note' => 'Bangladesh’s link into the trans-Asian network.'],
    ],

    'megaprojects' => [
        ['name' => 'Padma Multipurpose Bridge', 'opened' => 2022, 'stat' => '6.15 km', 'note' => 'Self-financed road-and-rail bridge connecting 21 southern districts to the capital.'],
        ['name' => 'Bangabandhu Sheikh Mujibur Rahman Tunnel', 'opened' => 2023, 'stat' => '3.32 km', 'note' => 'South Asia’s first underwater road tunnel, beneath the Karnaphuli river.'],
        ['name' => 'Dhaka Metro Rail (MRT Line 6)', 'opened' => 2022, 'stat' => '21.26 km', 'note' => 'The country’s first metro — Uttara North to Motijheel/Kamalapur.'],
        ['name' => 'Bangabandhu (Jamuna) Bridge', 'opened' => 1998, 'stat' => '4.8 km', 'note' => 'The bridge that first joined the north-west to the rest of the country.'],
        ['name' => 'Dhaka Elevated Expressway', 'opened' => 2023, 'stat' => '19.73 km', 'note' => 'Airport to Kutubkhali elevated corridor over the capital.'],
        ['name' => 'Rooppur Nuclear Power Plant', 'opened' => 2025, 'stat' => '2,400 MW', 'note' => 'The country’s first nuclear power station, at Ishwardi, Pabna.'],
    ],

    // ----------------------------------------------------------- transport
    'transport' => [
        ['mode' => 'Rickshaw', 'bn' => 'রিকশা', 'emoji' => '🛺', 'fare' => '৳20 – ৳80', 'where' => 'Every city and town', 'tip' => 'Always agree the fare before you sit. Dhaka alone runs on hundreds of thousands of them.'],
        ['mode' => 'CNG auto-rickshaw', 'bn' => 'সিএনজি', 'emoji' => '🛵', 'fare' => '৳60 – ৳400', 'where' => 'Cities and highways', 'tip' => 'Green three-wheelers running on compressed natural gas. Ask for the meter, or bargain firmly.'],
        ['mode' => 'Bus (city)', 'bn' => 'লোকাল বাস', 'emoji' => '🚌', 'fare' => '৳10 – ৳60', 'where' => 'Urban routes', 'tip' => 'Cheapest way to cross a city. Keep small notes ready for the helper.'],
        ['mode' => 'Bus (intercity AC)', 'bn' => 'দূরপাল্লার বাস', 'emoji' => '🚍', 'fare' => '৳500 – ৳2,500', 'where' => 'All districts', 'tip' => 'Green Line, Shohagh, Hanif and Ena run the main corridors. Book online in season.'],
        ['mode' => 'Train', 'bn' => 'ট্রেন', 'emoji' => '🚆', 'fare' => '৳200 – ৳1,500', 'where' => 'Bangladesh Railway network', 'tip' => 'Buy from eticket.railway.gov.bd exactly 10 days ahead — intercity seats sell out in minutes.'],
        ['mode' => 'Launch / Steamer', 'bn' => 'লঞ্চ', 'emoji' => '🛳️', 'fare' => '৳300 – ৳3,000', 'where' => 'Southern river routes', 'tip' => 'The overnight Dhaka–Barishal launch cabin is one of the great journeys of South Asia.'],
        ['mode' => 'Metro Rail', 'bn' => 'মেট্রোরেল', 'emoji' => '🚇', 'fare' => '৳20 – ৳100', 'where' => 'Dhaka MRT-6', 'tip' => 'Buy a rechargeable MRT Pass to skip the ticket queue at peak hours.'],
        ['mode' => 'Ride-sharing', 'bn' => 'রাইড শেয়ারিং', 'emoji' => '📱', 'fare' => '৳80 – ৳600', 'where' => 'Major cities', 'tip' => 'Uber, Pathao and Obhai. Bike rides are fastest through Dhaka traffic.'],
        ['mode' => 'Domestic flight', 'bn' => 'অভ্যন্তরীণ ফ্লাইট', 'emoji' => '✈️', 'fare' => '৳3,500 – ৳9,000', 'where' => '8 domestic airports', 'tip' => 'Biman, US-Bangla and Novoair fly to Chattogram, Cox’s Bazar, Sylhet, Jashore, Barishal, Rajshahi, Saidpur.'],
        ['mode' => 'Leguna / Tempo', 'bn' => 'লেগুনা', 'emoji' => '🚐', 'fare' => '৳10 – ৳40', 'where' => 'Short city hops', 'tip' => 'Crowded but quick shared minivans on fixed short routes.'],
    ],

    'airports' => [
        ['name' => 'Hazrat Shahjalal International Airport', 'code' => 'DAC', 'city' => 'Dhaka', 'type' => 'International', 'lat' => 23.8433, 'lon' => 90.3978],
        ['name' => 'Shah Amanat International Airport', 'code' => 'CGP', 'city' => 'Chattogram', 'type' => 'International', 'lat' => 22.2496, 'lon' => 91.8133],
        ['name' => 'Osmani International Airport', 'code' => 'ZYL', 'city' => 'Sylhet', 'type' => 'International', 'lat' => 24.9632, 'lon' => 91.8667],
        ['name' => "Cox's Bazar Airport", 'code' => 'CXB', 'city' => "Cox's Bazar", 'type' => 'Domestic', 'lat' => 21.4522, 'lon' => 91.9639],
        ['name' => 'Saidpur Airport', 'code' => 'SPD', 'city' => 'Nilphamari', 'type' => 'Domestic', 'lat' => 25.7592, 'lon' => 88.9089],
        ['name' => 'Jashore Airport', 'code' => 'JSR', 'city' => 'Jashore', 'type' => 'Domestic', 'lat' => 23.1838, 'lon' => 89.1608],
        ['name' => 'Barishal Airport', 'code' => 'BZL', 'city' => 'Barishal', 'type' => 'Domestic', 'lat' => 22.8010, 'lon' => 90.3012],
        ['name' => 'Shah Makhdum Airport', 'code' => 'RJH', 'city' => 'Rajshahi', 'type' => 'Domestic', 'lat' => 24.4372, 'lon' => 88.6165],
    ],

    'ports' => [
        ['name' => 'Chattogram Port', 'type' => 'Seaport', 'note' => 'Handles roughly 90% of the country’s external trade.', 'lat' => 22.3100, 'lon' => 91.8000],
        ['name' => 'Mongla Port', 'type' => 'Seaport', 'note' => 'Second seaport, serving the south-west and the Sundarbans region.', 'lat' => 22.4900, 'lon' => 89.6000],
        ['name' => 'Payra Port', 'type' => 'Seaport', 'note' => 'The newest deep-sea port, in Patuakhali.', 'lat' => 21.9800, 'lon' => 90.2500],
        ['name' => 'Benapole Land Port', 'type' => 'Land port', 'note' => 'The largest land border crossing with India.', 'lat' => 23.0430, 'lon' => 88.8890],
        ['name' => 'Banglabandha Land Port', 'type' => 'Land port', 'note' => 'Northern crossing linking Nepal and Bhutan corridors.', 'lat' => 26.6383, 'lon' => 88.3833],
    ],
];
