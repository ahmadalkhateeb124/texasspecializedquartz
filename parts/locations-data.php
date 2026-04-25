<?php
/**
 * parts/locations-data.php — Texas service-area data.
 *
 * Source of truth for every city we publish a landing page for.
 * Used by /service-areas, /locations/<slug>, sitemap.php, and the
 * LocalBusiness JSON-LD `areaServed` field.
 *
 * Add a new city → it appears everywhere automatically.
 *
 * Tier rules:
 *   "primary"   → owns rich landing page + sitemap entry + schema area + footer link
 *   "secondary" → schema area + sitemap, lighter content
 *   "extended"  → schema area only (Google sees, no dedicated page)
 */

return [
    /* ─────────────── DFW METROPLEX (PRIMARY market) ─────────────── */
    'dallas' => [
        'name' => 'Dallas',
        'county' => 'Dallas County',
        'state' => 'TX',
        'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 32.7767, 'lon' => -96.7970,
        'zips' => ['75201','75202','75204','75205','75206','75207','75209','75214','75219','75220','75225','75230','75231','75240'],
        'neighborhoods' => ['Highland Park','University Park','Lakewood','Preston Hollow','Oak Lawn','Bishop Arts','Uptown','Lake Highlands','M-Streets'],
        'tagline' => 'Dallas, TX — premier quartz, granite & marble countertops',
        'distance_min' => 25,
        'intro' => 'From Highland Park estates to Lakewood remodels, Dallas homeowners trust Texas Specialized Quartz & Granite for hand-selected stone slabs and CNC-precision fabrication. Our Carrollton showroom holds 300+ slabs in every color story — schedule a slab visit and we\'ll template, fabricate and install your kitchen countertops in as little as one week.',
    ],
    'plano' => [
        'name' => 'Plano',
        'county' => 'Collin County',
        'state' => 'TX', 'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 33.0198, 'lon' => -96.6989,
        'zips' => ['75023','75024','75025','75074','75075','75093','75094'],
        'neighborhoods' => ['West Plano','Legacy West','Willow Bend','Shepard Glen','Parker Road','Russell Creek'],
        'tagline' => 'Plano, TX — luxury quartz & granite countertops',
        'distance_min' => 18,
        'intro' => 'Plano kitchens deserve more than off-the-shelf surfaces. We supply premium quartz, exotic granite and rare quartzite to remodels across West Plano, Legacy West and Willow Bend — with same-week installation, free in-home measurement, and a lifetime craftsmanship warranty on every job.',
    ],
    'frisco' => [
        'name' => 'Frisco',
        'county' => 'Collin County',
        'state' => 'TX', 'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 33.1507, 'lon' => -96.8236,
        'zips' => ['75033','75034','75035','75036'],
        'neighborhoods' => ['Stonebriar','Frisco Lakes','Newman Village','Phillips Creek Ranch','The Trails'],
        'tagline' => 'Frisco, TX — custom quartz & granite countertops',
        'distance_min' => 22,
        'intro' => 'Frisco\'s booming home market means new builds, full remodels, and complex commercial fits. We bring 20+ years of stone fabrication experience to every Frisco project — from waterfall islands in Stonebriar to full builder-grade installs across Phillips Creek Ranch.',
    ],
    'carrollton' => [
        'name' => 'Carrollton',
        'county' => 'Dallas County',
        'state' => 'TX', 'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 32.9537, 'lon' => -96.8903,
        'zips' => ['75006','75007','75010'],
        'neighborhoods' => ['Old Downtown Carrollton','The Branch','Trinity Mills','Castle Hills'],
        'tagline' => 'Carrollton, TX — your local stone fabricator',
        'distance_min' => 0,
        'intro' => 'Our showroom and fabrication shop is right here in Carrollton — at 1225 W College Ave #616. Walk in any day to see 300+ slabs in person, talk through your project with a fabricator, and leave with same-day quotes for granite, quartz, marble and quartzite.',
    ],
    'allen' => [
        'name' => 'Allen',
        'county' => 'Collin County',
        'state' => 'TX', 'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 33.1031, 'lon' => -96.6706,
        'zips' => ['75002','75013'],
        'neighborhoods' => ['Twin Creeks','Star Creek','Watters Creek','Cumberland Crossing'],
        'tagline' => 'Allen, TX — granite & quartz countertop installation',
        'distance_min' => 25,
        'intro' => 'Whether you\'re refreshing a Twin Creeks kitchen or finishing a new Star Creek build, Allen homeowners get the same care every Texas Specialized Quartz client gets — slab visits, digital templating and finished countertops installed in days, not weeks.',
    ],
    'mckinney' => [
        'name' => 'McKinney',
        'county' => 'Collin County',
        'state' => 'TX', 'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 33.1972, 'lon' => -96.6398,
        'zips' => ['75069','75070','75071','75072'],
        'neighborhoods' => ['Stonebridge Ranch','Adriatica Village','Tucker Hill','Eldorado','Historic Downtown McKinney'],
        'tagline' => 'McKinney, TX — premium stone countertops',
        'distance_min' => 30,
        'intro' => 'McKinney\'s mix of historic homes and growing master-planned communities means every project is unique. From Stonebridge Ranch new construction to Historic Downtown remodels, we bring rare-import slabs and CNC fabrication to every McKinney install.',
    ],
    'richardson' => [
        'name' => 'Richardson',
        'county' => 'Dallas County',
        'state' => 'TX', 'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 32.9483, 'lon' => -96.7299,
        'zips' => ['75080','75081','75082','75083'],
        'neighborhoods' => ['Canyon Creek','Cottonwood Heights','Heights Park','Springpark'],
        'tagline' => 'Richardson, TX — kitchen countertop installation',
        'distance_min' => 20,
        'intro' => 'Richardson kitchens often pair mid-century bones with modern surfaces — exactly where quartz and lighter granites shine. Our showroom is 15 minutes from Canyon Creek; book a slab visit and start your remodel this week.',
    ],
    'fort-worth' => [
        'name' => 'Fort Worth',
        'county' => 'Tarrant County',
        'state' => 'TX', 'state_full' => 'Texas',
        'tier' => 'primary',
        'lat' => 32.7555, 'lon' => -97.3308,
        'zips' => ['76102','76104','76107','76109','76112','76116','76123','76244'],
        'neighborhoods' => ['Cultural District','Westover Hills','Rivercrest','TCU','Tanglewood'],
        'tagline' => 'Fort Worth, TX — granite & quartz fabrication',
        'distance_min' => 45,
        'intro' => 'Fort Worth\'s mix of cattle-country charm and modern lofts demands surfaces that hold up to real life. Our trucks deliver across Tarrant County weekly — granite for ranch kitchens, soft-veined quartz for downtown lofts, and durable quartzite for outdoor kitchens.',
    ],

    /* ─────────────── DFW (SECONDARY — covered, lighter pages) ─────────────── */
    'irving'         => ['name' => 'Irving',         'county' => 'Dallas County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.8140, 'lon' => -96.9489, 'zips' => ['75038','75039','75060','75061','75062','75063'], 'neighborhoods' => ['Las Colinas','Valley Ranch'], 'distance_min' => 20],
    'garland'        => ['name' => 'Garland',        'county' => 'Dallas County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.9126, 'lon' => -96.6389, 'zips' => ['75040','75041','75042','75043'], 'neighborhoods' => ['Firewheel','Embree'], 'distance_min' => 30],
    'arlington'      => ['name' => 'Arlington',      'county' => 'Tarrant County', 'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.7357, 'lon' => -97.1081, 'zips' => ['76001','76006','76010','76011','76012','76013','76014','76015','76016','76017','76018'], 'neighborhoods' => ['North Arlington','Pantego','Dalworthington Gardens'], 'distance_min' => 40],
    'lewisville'     => ['name' => 'Lewisville',     'county' => 'Denton County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 33.0462, 'lon' => -96.9942, 'zips' => ['75056','75057','75067','75077'], 'neighborhoods' => ['Castle Hills','Old Town'], 'distance_min' => 12],
    'flower-mound'   => ['name' => 'Flower Mound',   'county' => 'Denton County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 33.0146, 'lon' => -97.0969, 'zips' => ['75022','75028'], 'neighborhoods' => ['Bridlewood','Chinn Chapel'], 'distance_min' => 18],
    'coppell'        => ['name' => 'Coppell',        'county' => 'Dallas County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.9546, 'lon' => -96.9900, 'zips' => ['75019'],                       'neighborhoods' => ['Old Coppell','River Chase'], 'distance_min' => 10],
    'grapevine'      => ['name' => 'Grapevine',      'county' => 'Tarrant County', 'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.9343, 'lon' => -97.0780, 'zips' => ['76051','76092'],            'neighborhoods' => ['Historic Main Street','Silver Lake'], 'distance_min' => 22],
    'southlake'      => ['name' => 'Southlake',      'county' => 'Tarrant County', 'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.9412, 'lon' => -97.1342, 'zips' => ['76092'],                     'neighborhoods' => ['Town Square','Carillon'], 'distance_min' => 28],
    'prosper'        => ['name' => 'Prosper',        'county' => 'Collin County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 33.2362, 'lon' => -96.8011, 'zips' => ['75078'],                     'neighborhoods' => ['Whitley Place','Lakes of La Cima'], 'distance_min' => 30],
    'celina'         => ['name' => 'Celina',         'county' => 'Collin County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 33.3247, 'lon' => -96.7847, 'zips' => ['75009'],                     'neighborhoods' => ['Light Farms','Mustang Lakes'], 'distance_min' => 35],
    'rockwall'       => ['name' => 'Rockwall',       'county' => 'Rockwall County','state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.9312, 'lon' => -96.4597, 'zips' => ['75032','75087'],            'neighborhoods' => ['Chandlers Landing','The Shores'], 'distance_min' => 45],
    'addison'        => ['name' => 'Addison',        'county' => 'Dallas County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.9618, 'lon' => -96.8292, 'zips' => ['75001'],                     'neighborhoods' => ['Addison Circle'], 'distance_min' => 8],
    'farmers-branch' => ['name' => 'Farmers Branch', 'county' => 'Dallas County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.9264, 'lon' => -96.8961, 'zips' => ['75234','75244'],            'neighborhoods' => ['Brookhaven','Mercer Crossing'], 'distance_min' => 5],
    'highland-park'  => ['name' => 'Highland Park',  'county' => 'Dallas County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.8327, 'lon' => -96.7919, 'zips' => ['75205'],                     'neighborhoods' => ['Highland Park Village'], 'distance_min' => 25],
    'university-park' => ['name' => 'University Park','county' => 'Dallas County', 'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.8501, 'lon' => -96.7969, 'zips' => ['75205','75225'],           'neighborhoods' => ['SMU','Snider Plaza'], 'distance_min' => 25],
    'mesquite'       => ['name' => 'Mesquite',       'county' => 'Dallas County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'secondary', 'lat' => 32.7668, 'lon' => -96.5992, 'zips' => ['75149','75150'],           'neighborhoods' => ['Town East','Bruton Terrace'], 'distance_min' => 35],

    /* ─────────────── EXTENDED — schema/areaServed only (we'll travel) ─────────────── */
    'houston'      => ['name' => 'Houston',      'county' => 'Harris County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 29.7604, 'lon' => -95.3698],
    'austin'       => ['name' => 'Austin',       'county' => 'Travis County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 30.2672, 'lon' => -97.7431],
    'san-antonio'  => ['name' => 'San Antonio',  'county' => 'Bexar County',    'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 29.4241, 'lon' => -98.4936],
    'el-paso'      => ['name' => 'El Paso',      'county' => 'El Paso County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 31.7619, 'lon' => -106.4850],
    'corpus-christi' => ['name' => 'Corpus Christi','county' => 'Nueces County','state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 27.8006, 'lon' => -97.3964],
    'lubbock'      => ['name' => 'Lubbock',      'county' => 'Lubbock County',  'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.5779, 'lon' => -101.8552],
    'amarillo'     => ['name' => 'Amarillo',     'county' => 'Potter County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 35.2220, 'lon' => -101.8313],
    'waco'         => ['name' => 'Waco',         'county' => 'McLennan County', 'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 31.5493, 'lon' => -97.1467],
    'tyler'        => ['name' => 'Tyler',        'county' => 'Smith County',    'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 32.3513, 'lon' => -95.3011],
    'denton'       => ['name' => 'Denton',       'county' => 'Denton County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.2148, 'lon' => -97.1331],
    'wylie'        => ['name' => 'Wylie',        'county' => 'Collin County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.0151, 'lon' => -96.5388],
    'sachse'       => ['name' => 'Sachse',       'county' => 'Dallas County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 32.9762, 'lon' => -96.5953],
    'murphy'       => ['name' => 'Murphy',       'county' => 'Collin County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.0148, 'lon' => -96.6131],
    'parker'       => ['name' => 'Parker',       'county' => 'Collin County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.0462, 'lon' => -96.6228],
    'lucas'        => ['name' => 'Lucas',        'county' => 'Collin County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.0876, 'lon' => -96.5772],
    'fairview'     => ['name' => 'Fairview',     'county' => 'Collin County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.1442, 'lon' => -96.6181],
    'little-elm'   => ['name' => 'Little Elm',   'county' => 'Denton County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.1626, 'lon' => -96.9376],
    'the-colony'   => ['name' => 'The Colony',   'county' => 'Denton County',   'state' => 'TX', 'state_full' => 'Texas', 'tier' => 'extended', 'lat' => 33.0806, 'lon' => -96.8864],
];
