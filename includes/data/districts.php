<?php
/**
 * All 64 districts of Bangladesh grouped by the 8 administrative divisions.
 * Coordinates are district-headquarter centroids, used by the SVG map projection.
 */

return [
    'divisions' => [
        'Dhaka'      => ['lat' => 23.8103, 'lon' => 90.4125, 'established' => 1829, 'area' => 20508, 'population' => 44215000, 'color' => '#00a651'],
        'Chattogram' => ['lat' => 22.3569, 'lon' => 91.7832, 'established' => 1829, 'area' => 33909, 'population' => 33202000, 'color' => '#f42a41'],
        'Rajshahi'   => ['lat' => 24.3745, 'lon' => 88.6042, 'established' => 1829, 'area' => 18153, 'population' => 20353000, 'color' => '#0f9b8e'],
        'Khulna'     => ['lat' => 22.8456, 'lon' => 89.5403, 'established' => 1960, 'area' => 22285, 'population' => 17416000, 'color' => '#f7941d'],
        'Barishal'   => ['lat' => 22.7010, 'lon' => 90.3535, 'established' => 1993, 'area' => 13297, 'population' => 9325000,  'color' => '#8e44ad'],
        'Sylhet'     => ['lat' => 24.8949, 'lon' => 91.8687, 'established' => 1995, 'area' => 12596, 'population' => 11362000, 'color' => '#2980b9'],
        'Rangpur'    => ['lat' => 25.7439, 'lon' => 89.2752, 'established' => 2010, 'area' => 16185, 'population' => 17610000, 'color' => '#c0392b'],
        'Mymensingh' => ['lat' => 24.7471, 'lon' => 90.4203, 'established' => 2015, 'area' => 10485, 'population' => 12369000, 'color' => '#16a085'],
    ],

    'districts' => [
        // ---------------------------------------------------------- Dhaka
        ['name' => 'Dhaka',          'bn' => 'ঢাকা',           'division' => 'Dhaka', 'lat' => 23.8103, 'lon' => 90.4125, 'area' => 1463.6, 'population' => 12043977, 'famous' => 'Capital city, Lalbagh Fort, Ahsan Manzil, muslin heritage'],
        ['name' => 'Gazipur',        'bn' => 'গাজীপুর',        'division' => 'Dhaka', 'lat' => 24.0958, 'lon' => 90.4125, 'area' => 1806.4, 'population' => 5263474,  'famous' => 'Bhawal National Park, garment industry hub'],
        ['name' => 'Kishoreganj',    'bn' => 'কিশোরগঞ্জ',      'division' => 'Dhaka', 'lat' => 24.4449, 'lon' => 90.7766, 'area' => 2688.6, 'population' => 3267630,  'famous' => 'Sholakia Eid ground, Haor wetlands, Nikli'],
        ['name' => 'Manikganj',      'bn' => 'মানিকগঞ্জ',      'division' => 'Dhaka', 'lat' => 23.8644, 'lon' => 90.0047, 'area' => 1383.7, 'population' => 1558154,  'famous' => 'Baliati Palace, Padma river bank'],
        ['name' => 'Munshiganj',     'bn' => 'মুন্সিগঞ্জ',      'division' => 'Dhaka', 'lat' => 23.5422, 'lon' => 90.5305, 'area' => 954.9,  'population' => 1625000,  'famous' => 'Idrakpur Fort, Atish Dipankar birthplace, potato belt'],
        ['name' => 'Narayanganj',    'bn' => 'নারায়ণগঞ্জ',    'division' => 'Dhaka', 'lat' => 23.6238, 'lon' => 90.5000, 'area' => 684.4,  'population' => 3909138,  'famous' => 'Dhaka of the East, Panam City, jamdani weaving'],
        ['name' => 'Narsingdi',      'bn' => 'নরসিংদী',        'division' => 'Dhaka', 'lat' => 23.9322, 'lon' => 90.7150, 'area' => 1140.8, 'population' => 2584452,  'famous' => 'Wari-Bateshwar ruins, textile trade'],
        ['name' => 'Tangail',        'bn' => 'টাঙ্গাইল',        'division' => 'Dhaka', 'lat' => 24.2513, 'lon' => 89.9167, 'area' => 3414.3, 'population' => 4037608,  'famous' => 'Tangail saree, Atia Mosque, Madhupur forest'],
        ['name' => 'Faridpur',       'bn' => 'ফরিদপুর',        'division' => 'Dhaka', 'lat' => 23.6070, 'lon' => 89.8429, 'area' => 2072.7, 'population' => 2162876,  'famous' => 'Jute capital, Pathrail, Padma bridge approach'],
        ['name' => 'Gopalganj',      'bn' => 'গোপালগঞ্জ',      'division' => 'Dhaka', 'lat' => 23.0050, 'lon' => 89.8266, 'area' => 1490.0, 'population' => 1295000,  'famous' => 'Tungipara, Baghiar Bill water lilies'],
        ['name' => 'Madaripur',      'bn' => 'মাদারীপুর',      'division' => 'Dhaka', 'lat' => 23.1641, 'lon' => 90.1897, 'area' => 1145.0, 'population' => 1300000,  'famous' => 'Shakuni Lake, Auliapur Mazar'],
        ['name' => 'Rajbari',        'bn' => 'রাজবাড়ী',        'division' => 'Dhaka', 'lat' => 23.7574, 'lon' => 89.6444, 'area' => 1118.8, 'population' => 1160000,  'famous' => 'Daulatdia ferry ghat, Goalanda hilsa'],
        ['name' => 'Shariatpur',     'bn' => 'শরীয়তপুর',      'division' => 'Dhaka', 'lat' => 23.2423, 'lon' => 90.4348, 'area' => 1181.5, 'population' => 1250000,  'famous' => 'Padma bridge south end, Mahisar Char'],

        // ----------------------------------------------------- Chattogram
        ['name' => 'Chattogram',     'bn' => 'চট্টগ্রাম',       'division' => 'Chattogram', 'lat' => 22.3569, 'lon' => 91.7832, 'area' => 5282.9, 'population' => 9169618, 'famous' => 'Port city, Patenga beach, Foys Lake, mezban'],
        ['name' => "Cox's Bazar",    'bn' => 'কক্সবাজার',      'division' => 'Chattogram', 'lat' => 21.4272, 'lon' => 92.0058, 'area' => 2491.9, 'population' => 2823265, 'famous' => "World's longest natural sea beach (120 km)"],
        ['name' => 'Bandarban',      'bn' => 'বান্দরবান',      'division' => 'Chattogram', 'lat' => 22.1953, 'lon' => 92.2184, 'area' => 4479.0, 'population' => 481109,  'famous' => 'Tazing Dong, Nilgiri, Boga Lake, hill tribes'],
        ['name' => 'Rangamati',      'bn' => 'রাঙ্গামাটি',      'division' => 'Chattogram', 'lat' => 22.6533, 'lon' => 92.1735, 'area' => 6116.1, 'population' => 647587,  'famous' => 'Kaptai Lake, Hanging Bridge, Sajek gateway'],
        ['name' => 'Khagrachhari',   'bn' => 'খাগড়াছড়ি',      'division' => 'Chattogram', 'lat' => 23.1193, 'lon' => 91.9847, 'area' => 2699.6, 'population' => 714000,  'famous' => 'Sajek Valley, Alutila Cave, Richang waterfall'],
        ['name' => 'Feni',           'bn' => 'ফেনী',           'division' => 'Chattogram', 'lat' => 23.0159, 'lon' => 91.3976, 'area' => 990.4,  'population' => 1650000, 'famous' => 'Muhuri project, Sonagazi coastline'],
        ['name' => 'Lakshmipur',     'bn' => 'লক্ষ্মীপুর',      'division' => 'Chattogram', 'lat' => 22.9447, 'lon' => 90.8282, 'area' => 1455.9, 'population' => 1900000, 'famous' => 'Meghna estuary, soybean capital'],
        ['name' => 'Noakhali',       'bn' => 'নোয়াখালী',      'division' => 'Chattogram', 'lat' => 22.8696, 'lon' => 91.0995, 'area' => 3600.9, 'population' => 3625000, 'famous' => 'Nijhum Dwip, Bajra Shahi Mosque'],
        ['name' => 'Cumilla',        'bn' => 'কুমিল্লা',        'division' => 'Chattogram', 'lat' => 23.4607, 'lon' => 91.1809, 'area' => 3085.2, 'population' => 6212216, 'famous' => 'Mainamati Buddhist ruins, rasmalai'],
        ['name' => 'Chandpur',       'bn' => 'চাঁদপুর',        'division' => 'Chattogram', 'lat' => 23.2333, 'lon' => 90.6712, 'area' => 1704.1, 'population' => 2600000, 'famous' => 'Hilsa capital, Meghna-Padma confluence'],
        ['name' => 'Brahmanbaria',   'bn' => 'ব্রাহ্মণবাড়িয়া',  'division' => 'Chattogram', 'lat' => 23.9571, 'lon' => 91.1119, 'area' => 1927.1, 'population' => 3300000, 'famous' => 'Music heritage, Akhaura land port'],

        // ------------------------------------------------------- Rajshahi
        ['name' => 'Rajshahi',       'bn' => 'রাজশাহী',        'division' => 'Rajshahi', 'lat' => 24.3745, 'lon' => 88.6042, 'area' => 2407.0, 'population' => 2915000, 'famous' => 'Silk city, mango capital, Padma Garden'],
        ['name' => 'Bogura',         'bn' => 'বগুড়া',          'division' => 'Rajshahi', 'lat' => 24.8465, 'lon' => 89.3773, 'area' => 2919.9, 'population' => 3800000, 'famous' => 'Mahasthangarh (oldest city), doi (sweet yogurt)'],
        ['name' => 'Joypurhat',      'bn' => 'জয়পুরহাট',      'division' => 'Rajshahi', 'lat' => 25.0947, 'lon' => 89.0227, 'area' => 965.4,  'population' => 1000000, 'famous' => 'Pathorghata temple, sugar mill'],
        ['name' => 'Naogaon',        'bn' => 'নওগাঁ',          'division' => 'Rajshahi', 'lat' => 24.7936, 'lon' => 88.9318, 'area' => 3435.7, 'population' => 2750000, 'famous' => 'Paharpur Somapura Mahavihara (UNESCO)'],
        ['name' => 'Natore',         'bn' => 'নাটোর',          'division' => 'Rajshahi', 'lat' => 24.4206, 'lon' => 89.0003, 'area' => 1900.1, 'population' => 1900000, 'famous' => 'Uttara Gonobhaban, Natore Rajbari, kanchagolla'],
        ['name' => 'Chapainawabganj','bn' => 'চাঁপাইনবাবগঞ্জ', 'division' => 'Rajshahi', 'lat' => 24.5965, 'lon' => 88.2775, 'area' => 1702.6, 'population' => 1800000, 'famous' => 'Mango capital, Choto Sona Mosque, Gaur ruins'],
        ['name' => 'Pabna',          'bn' => 'পাবনা',          'division' => 'Rajshahi', 'lat' => 24.0064, 'lon' => 89.2372, 'area' => 2371.5, 'population' => 2800000, 'famous' => 'Hardinge Bridge, Ishwardi, Rooppur nuclear plant'],
        ['name' => 'Sirajganj',      'bn' => 'সিরাজগঞ্জ',      'division' => 'Rajshahi', 'lat' => 24.4534, 'lon' => 89.7007, 'area' => 2497.9, 'population' => 3300000, 'famous' => 'Bangabandhu Bridge, handloom weaving'],

        // --------------------------------------------------------- Khulna
        ['name' => 'Khulna',         'bn' => 'খুলনা',          'division' => 'Khulna', 'lat' => 22.8456, 'lon' => 89.5403, 'area' => 4394.5, 'population' => 2600000, 'famous' => 'Sundarbans gateway, Rupsha bridge, shrimp trade'],
        ['name' => 'Bagerhat',       'bn' => 'বাগেরহাট',       'division' => 'Khulna', 'lat' => 22.6516, 'lon' => 89.7859, 'area' => 3959.1, 'population' => 1500000, 'famous' => 'Sixty Dome Mosque (UNESCO), Khan Jahan Ali'],
        ['name' => 'Chuadanga',      'bn' => 'চুয়াডাঙ্গা',     'division' => 'Khulna', 'lat' => 23.6402, 'lon' => 88.8418, 'area' => 1174.1, 'population' => 1300000, 'famous' => 'Hottest district, first capital of Mujibnagar era'],
        ['name' => 'Jashore',        'bn' => 'যশোর',           'division' => 'Khulna', 'lat' => 23.1664, 'lon' => 89.2081, 'area' => 2606.9, 'population' => 3000000, 'famous' => 'First liberated district, flower village Godkhali'],
        ['name' => 'Jhenaidah',      'bn' => 'ঝিনাইদহ',        'division' => 'Khulna', 'lat' => 23.5448, 'lon' => 89.1539, 'area' => 1964.8, 'population' => 1950000, 'famous' => 'Nalduri, Miah Bari mosque, date molasses'],
        ['name' => 'Kushtia',        'bn' => 'কুষ্টিয়া',       'division' => 'Khulna', 'lat' => 23.9013, 'lon' => 89.1206, 'area' => 1621.2, 'population' => 2150000, 'famous' => 'Lalon Shah shrine, Shilaidaha Kuthibari (Tagore)'],
        ['name' => 'Magura',         'bn' => 'মাগুরা',         'division' => 'Khulna', 'lat' => 23.4855, 'lon' => 89.4198, 'area' => 1048.6, 'population' => 1000000, 'famous' => 'Siddheshwari temple, Nabaganga river'],
        ['name' => 'Meherpur',       'bn' => 'মেহেরপুর',       'division' => 'Khulna', 'lat' => 23.7622, 'lon' => 88.6318, 'area' => 716.1,  'population' => 700000,  'famous' => 'Mujibnagar — first government of Bangladesh, 1971'],
        ['name' => 'Narail',         'bn' => 'নড়াইল',         'division' => 'Khulna', 'lat' => 23.1728, 'lon' => 89.5127, 'area' => 990.2,  'population' => 750000,  'famous' => 'S. M. Sultan art gallery, Chitra river'],
        ['name' => 'Satkhira',       'bn' => 'সাতক্ষীরা',      'division' => 'Khulna', 'lat' => 22.7185, 'lon' => 89.0705, 'area' => 3858.3, 'population' => 2200000, 'famous' => 'Sundarbans west, Mozaffar mango, shrimp farms'],

        // ------------------------------------------------------- Barishal
        ['name' => 'Barishal',       'bn' => 'বরিশাল',         'division' => 'Barishal', 'lat' => 22.7010, 'lon' => 90.3535, 'area' => 2790.5, 'population' => 2500000, 'famous' => 'Venice of Bengal, floating guava market'],
        ['name' => 'Barguna',        'bn' => 'বরগুনা',         'division' => 'Barishal', 'lat' => 22.1596, 'lon' => 90.1264, 'area' => 1831.3, 'population' => 950000,  'famous' => 'Taltoli sea beach, Haringhata forest'],
        ['name' => 'Bhola',          'bn' => 'ভোলা',           'division' => 'Barishal', 'lat' => 22.6859, 'lon' => 90.6482, 'area' => 3403.5, 'population' => 1900000, 'famous' => 'Largest island district, Monpura, natural gas'],
        ['name' => 'Jhalokati',      'bn' => 'ঝালকাঠি',        'division' => 'Barishal', 'lat' => 22.6406, 'lon' => 90.1987, 'area' => 749.0,  'population' => 700000,  'famous' => 'Floating guava market of Bhimruli'],
        ['name' => 'Patuakhali',     'bn' => 'পটুয়াখালী',     'division' => 'Barishal', 'lat' => 22.3596, 'lon' => 90.3298, 'area' => 3220.2, 'population' => 1750000, 'famous' => 'Kuakata — sunrise and sunset from one beach'],
        ['name' => 'Pirojpur',       'bn' => 'পিরোজপুর',       'division' => 'Barishal', 'lat' => 22.5791, 'lon' => 89.9759, 'area' => 1307.6, 'population' => 1150000, 'famous' => 'Floating seedling market, Bhandaria'],

        // --------------------------------------------------------- Sylhet
        ['name' => 'Sylhet',         'bn' => 'সিলেট',          'division' => 'Sylhet', 'lat' => 24.8949, 'lon' => 91.8687, 'area' => 3452.1, 'population' => 3900000, 'famous' => 'Tea gardens, Ratargul swamp, Jaflong, Shah Jalal shrine'],
        ['name' => 'Habiganj',       'bn' => 'হবিগঞ্জ',        'division' => 'Sylhet', 'lat' => 24.3745, 'lon' => 91.4155, 'area' => 2636.6, 'population' => 2350000, 'famous' => 'Satchari National Park, gas fields'],
        ['name' => 'Moulvibazar',    'bn' => 'মৌলভীবাজার',     'division' => 'Sylhet', 'lat' => 24.4829, 'lon' => 91.7774, 'area' => 2799.4, 'population' => 2100000, 'famous' => 'Sreemangal — tea capital, Lawachara rainforest, Madhabkunda'],
        ['name' => 'Sunamganj',      'bn' => 'সুনামগঞ্জ',      'division' => 'Sylhet', 'lat' => 25.0658, 'lon' => 91.3950, 'area' => 3747.2, 'population' => 2700000, 'famous' => 'Tanguar Haor (Ramsar site), Niladri Lake, Barikka Tila'],

        // -------------------------------------------------------- Rangpur
        ['name' => 'Rangpur',        'bn' => 'রংপুর',          'division' => 'Rangpur', 'lat' => 25.7439, 'lon' => 89.2752, 'area' => 2400.6, 'population' => 3200000, 'famous' => 'Tajhat Palace, hariwal, tobacco belt'],
        ['name' => 'Dinajpur',       'bn' => 'দিনাজপুর',       'division' => 'Rangpur', 'lat' => 25.6217, 'lon' => 88.6354, 'area' => 3437.9, 'population' => 3200000, 'famous' => 'Kantajew Temple, Ramsagar Lake, litchi'],
        ['name' => 'Gaibandha',      'bn' => 'গাইবান্ধা',      'division' => 'Rangpur', 'lat' => 25.3288, 'lon' => 89.5281, 'area' => 2179.3, 'population' => 2600000, 'famous' => 'Friendship Centre, Brahmaputra chars'],
        ['name' => 'Kurigram',       'bn' => 'কুড়িগ্রাম',      'division' => 'Rangpur', 'lat' => 25.8072, 'lon' => 89.6362, 'area' => 2245.0, 'population' => 2200000, 'famous' => 'Chilmari port, river chars, Dharla river'],
        ['name' => 'Lalmonirhat',    'bn' => 'লালমনিরহাট',     'division' => 'Rangpur', 'lat' => 25.9923, 'lon' => 89.2847, 'area' => 1247.4, 'population' => 1450000, 'famous' => 'Burimari land port, Tin Bigha corridor'],
        ['name' => 'Nilphamari',     'bn' => 'নীলফামারী',      'division' => 'Rangpur', 'lat' => 25.9310, 'lon' => 88.8560, 'area' => 1546.6, 'population' => 2000000, 'famous' => 'Saidpur railway workshop, Nilsagar'],
        ['name' => 'Panchagarh',     'bn' => 'পঞ্চগড়',        'division' => 'Rangpur', 'lat' => 26.3411, 'lon' => 88.5542, 'area' => 1404.6, 'population' => 1100000, 'famous' => 'Northernmost point, Kanchenjunga view, Tetulia tea'],
        ['name' => 'Thakurgaon',     'bn' => 'ঠাকুরগাঁও',      'division' => 'Rangpur', 'lat' => 26.0337, 'lon' => 88.4616, 'area' => 1809.5, 'population' => 1500000, 'famous' => 'Jamalpur Zamindar mosque, Baliadangi mango tree'],

        // ----------------------------------------------------- Mymensingh
        ['name' => 'Mymensingh',     'bn' => 'ময়মনসিংহ',      'division' => 'Mymensingh', 'lat' => 24.7471, 'lon' => 90.4203, 'area' => 4363.5, 'population' => 5700000, 'famous' => 'Brahmaputra bank, Shashi Lodge, agricultural university'],
        ['name' => 'Jamalpur',       'bn' => 'জামালপুর',       'division' => 'Mymensingh', 'lat' => 24.9375, 'lon' => 89.9372, 'area' => 2031.9, 'population' => 2500000, 'famous' => 'Nakshi kantha embroidery, Doyamoyee temple'],
        ['name' => 'Netrokona',      'bn' => 'নেত্রকোণা',      'division' => 'Mymensingh', 'lat' => 24.8703, 'lon' => 90.7279, 'area' => 2794.3, 'population' => 2400000, 'famous' => 'Birishiri china-clay hills, Somesshwari river'],
        ['name' => 'Sherpur',        'bn' => 'শেরপুর',         'division' => 'Mymensingh', 'lat' => 25.0205, 'lon' => 90.0153, 'area' => 1359.7, 'population' => 1500000, 'famous' => 'Gajni hills, Madhutila eco-park, Garo culture'],
    ],
];
