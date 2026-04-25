<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texas Specialized Quartz & Granite — Sinks</title>
    <!-- Site fonts loaded by parts/header.php (theme-2026.css) -->
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}


        /* ── HERO ── */
        .stone-hero{
            max-width:1200px;
            margin:0 auto;
            padding:2.5rem 1.5rem 1rem;
        }
        .stone-hero h1{
            font-family:var(--tx-serif);
            font-size:clamp(38px,4vw,52px);
            font-weight:400;
            line-height:1.25;
            color:#1a1814;
            margin-bottom:1rem;
        }
        .stone-hero h1 em{
            font-style:normal;
            color:#999999;
        }
        .stone-hero p{
            font-size:18px;
            color:#5c5650;
            max-width:680px;
            line-height:1.7;
            margin-bottom:.4rem;
        }
        .phone-link{
            display:inline-flex;
            align-items:center;
            gap:8px;
            margin-top:1rem;
            font-size:13px;
            color:#5c5650;
        }
        .phone-link a{
            color:#999999;
            font-weight:600;
            text-decoration:none;
        }
        .phone-link a:hover{text-decoration:underline;}

        /* ── FEATURES STRIP ── */
        .features-strip{
            max-width:1200px;
            margin:1.5rem auto 0;
            padding:0 1.5rem;
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:10px;
        }
        .feat-item{
            background: rgb(111 111 111 / 0.11);
            border-radius:10px;
            padding:.85rem 1rem;
        }
        .feat-label{
            font-size:10px;
            color:#8c877f;
            text-transform:uppercase;
            letter-spacing:.7px;
            margin-bottom:4px;
        }
        .feat-val{
            font-size:13px;
            font-weight:600;
            color:#1a1814;
        }

        /* ── FILTER BAR ── */
        .filter-bar{
            max-width:1200px;
            margin:2rem auto 0;
            padding:0 1.5rem;
            display:flex;
            flex-wrap:wrap;
            gap:8px;
        }
        .filter-btn{
            padding:8px 20px;
            border-radius:50px;
            border:1.5px solid #d8d2c8;
            background:transparent;
            font-family:var(--tx-sans);
            font-size:13px;
            font-weight:500;
            color:#5c5650;
            cursor:pointer;
            transition:all .25s ease;
            white-space:nowrap;
        }
        .filter-btn:hover{
            border-color:#999999;
            color:#999999;
        }
        .filter-btn.active{
            background:#999999;
            border-color:#999999;
            color:#fff;
        }

        /* ── CATALOG CONTAINER ── */
        .catalog{
            max-width:1200px;
            margin:0 auto;
            padding:0 1.5rem 3rem;
        }

        /* ── SECTION ── */
        .section-block{
            margin-top:2rem;
        }
        .section-header{
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom:.35rem;
        }
        .section-header h2{
            font-family:var(--tx-serif);
            font-size:22px;
            font-weight:400;
            color:#1a1814;
        }
        .section-divider{
            height:1px;
            background:#e8e2d9;
            margin-bottom:1.25rem;
        }

        /* ── SUB SECTION ── */
        .sub-header{
            display:flex;
            align-items:center;
            gap:8px;
            margin:1.5rem 0 1rem;
        }
        .sub-header::before{
            content:'';
            width:3px;
            height:16px;
            background:#999999;
            border-radius:2px;
            flex-shrink:0;
        }
        .sub-header h3{
            font-size:20px;
            font-weight:600;
            color:#1a1814;
        }

        /* ── GRID ── */
        .card-grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:12px;
        }

        /* ── CARD ── */
        .stone-card{
            background:#fff;
            border:1px solid #e8e2d9;
            border-radius:10px;
            overflow:hidden;
            transition:border-color .2s, box-shadow .25s, transform .25s;
            cursor:pointer;
        }
        .stone-card:hover{
            border-color:#c4b89a;
            box-shadow:0 6px 24px rgba(0,0,0,.08);
            transform:translateY(-2px);
        }
        .img-wrap{
            position:relative;
            aspect-ratio:4/4;
            background:#ffffff;
            overflow:hidden;
        }
        .img-wrap img{
            width:100%;
            height:100%;
            object-fit:contain;
            display:block;
            padding:6px;
            transition:transform .35s ease;
        }
        .stone-card:hover .img-wrap img{
            transform:scale(1.05);
        }
        .img-placeholder{
            width:100%;
            height:100%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#ffffff;
        }
        .img-placeholder svg{opacity:.15;}
        .stone-info{
            padding:.6rem .75rem .7rem;
            border-top:1px solid #f0ece6;
        }
        .stone-info h4{
            font-size:16px;
            font-weight:600;
            color:#1a1814;
            line-height:1.35;
            margin:0;
        }

        /* ── ANIMATIONS ── */
        .section-block{
            animation:fadeUp .5s ease both;
        }
        .section-block.hidden{
            display:none;
        }
        @keyframes fadeUp{
            from{opacity:0;transform:translateY(16px);}
            to{opacity:1;transform:translateY(0);}
        }

        /* ── RESPONSIVE ── */
        @media(max-width:900px){
            .card-grid{grid-template-columns:repeat(3,1fr);}
        }
        @media(max-width:640px){
            .card-grid{grid-template-columns:repeat(2,1fr);gap:8px;}
            .features-strip{grid-template-columns:1fr 1fr;}
            .stone-hero h1{font-size:34px;}
        }
        @media(max-width:400px){
            .features-strip{grid-template-columns:1fr;}
        }
    </style>
</head>
<body>
<header class="page-header" data-background="images/Granit-Img/cccc.png" data-stellar-background-ratio="1.15">
    <div class="container ee">
        <h1>Premium <strong>Kitchen</strong>, <strong>Bathroom</strong> & <strong>Bar</strong> Sinks</h1>
    </div>
</header>
<!-- HERO -->
<div class="stone-hero">
    <h2>Premium Undermount Sinks —<br><em>Stainless Steel</em>, <em>Composite</em> & <em>Ceramic</em></h2>
    <p>At <strong>Texas Specialized Quartz & Granite</strong>, we offer a curated selection of undermount sinks designed to complement your countertop perfectly — from stainless steel kitchen sinks and granite composite bowls to elegant ceramic bathroom basins.</p>
    <p>Our sinks are built for durability, easy maintenance, and seamless integration with natural stone and engineered surfaces. Available in single bowl, double bowl, workstation, and modern corner styles.</p>
    <div class="phone-link">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 15a16 16 0 006 6l.61-.61a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
        Schedule a consultation: <a href="tel:+14698140555">+1 (469) 814-0555</a>
    </div>
</div>


<!-- FEATURES -->
<div class="features-strip">
    <div class="feat-item">
        <div class="feat-label">Sink types</div>
        <div class="feat-val">SS · Composite · Ceramic</div>
    </div>
    <div class="feat-item">
        <div class="feat-label">Styles</div>
        <div class="feat-val">Standard · Corner · Workstation</div>
    </div>
    <div class="feat-item">
        <div class="feat-label">Applications</div>
        <div class="feat-val">Kitchen · Bathroom · Bar · Laundry</div>
    </div>
</div>

<!-- FILTER -->
<div class="filter-bar">
    <button class="filter-btn active" data-filter="all">All</button>
    <button class="filter-btn" data-filter="kitchen">Kitchen Sink</button>
    <button class="filter-btn" data-filter="bathroom">Bathroom Sink</button>
    <button class="filter-btn" data-filter="bar">Bar Sink</button>
    <button class="filter-btn" data-filter="laundry">Laundry Sink</button>
</div>

<!-- CATALOG -->
<div class="catalog">

    <!-- ═══ KITCHEN SINK ═══ -->
    <div class="section-block" data-category="kitchen">
        <div class="section-header"><h2>Kitchen Sink</h2></div>
        <div class="section-divider"></div>

        <div class="sub-header"><h3>Undermount Standard Sinks</h3></div>
        <div class="card-grid">
            <div class="stone-card">
                <div class="img-wrap"><img src="images/Undermountstandardsinks/ls-78-single-bowl-kitchen-sink.jpg" alt="LS-78" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-78 Single Bowl Kitchen Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/Undermountstandardsinks/ls-88-double-bowl-kitchen-sink.jpg" alt="LS-88" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-88 Double Bowl Kitchen Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/Undermountstandardsinks/ls-68-double-bowl-kitchen-sink.png" alt="LS-68" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-68 Double Bowl Kitchen Sink</h4></div>
            </div>
        </div>

        <div class="sub-header"><h3>Undermount Modern Corner Sinks</h3></div>
        <div class="card-grid">
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountModernCornerSinks/ls-H78-single-bowl-ada-kitchen-sink.jpg" alt="LS-H78" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-H78 Single Bowl Modern Corner SS Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountModernCornerSinks/ls-h88-degree-double-bowl-zero-radius-sink.jpg" alt="LS-H88" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-H88 50/50 Modern Corner SS Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountModernCornerSinks/ls-h68-degree-double-bowl-zero-radius-sink.jpg" alt="LS-H68" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-H68 60/40 Modern Corner SS Sink</h4></div>
            </div>
        </div>

        <div class="sub-header"><h3>Undermount Workstation Sink</h3></div>
        <div class="card-grid">
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountWorkstationSink/LS-H3219-1-degree-apron-front-farm-sink.jpg" alt="LS-H3219-1" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-H3219-1 Single Bowl Workstation SS Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountWorkstationSink/LS-H3419D-degree-apron-front-farm-sink.jpg" alt="LS-H3419D" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-H3419D Double Bowl Workstation SS Sink</h4></div>
            </div>
        </div>

        <div class="sub-header"><h3>Undermount Composite Sink</h3></div>
        <div class="card-grid">
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/granite-composite-undermount-sink-double-bowl-Coffee.jpg" alt="GC78 Coffee" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC78 Single Bowl Composite – Coffee</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/granite-composite-undermount-sink-double-bowl-white.jpg" alt="GC78 White" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC78 Single Bowl Composite – White</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/granite-composite-undermount-sink-double-bowl-gray.jpg" alt="GC78 Gray" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC78 Single Bowl Composite – Gray</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/granite-composite-undermount-sink-double-bowl-black.jpg" alt="GC78 Black" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC78 Single Bowl Composite – Black</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/LS-GC88granite-composite-undermount-sink-double-bowl-black.jpg" alt="GC88 Coffee" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC88 50/50 Composite – Coffee</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/LS-GC88-granite-composite-undermount-sink-double-bowl-black.jpg" alt="GC88 White" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC88 50/50 Composite – White</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/LS-GC88-undermount-sink-double-bowl-gray.jpg" alt="GC88 Gray" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC88 50/50 Composite – Gray</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/UndermountCompositeSink/LS-GC88-undermount-sink-double-bowl-black.jpg" alt="GC88 Black" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-GC88 50/50 Composite – Black</h4></div>
            </div>
        </div>
    </div>

    <!-- ═══ BATHROOM SINK ═══ -->
    <div class="section-block" data-category="bathroom">
        <div class="section-header"><h2>Bathroom Sink</h2></div>
        <div class="section-divider"></div>

        <div class="sub-header"><h3>Undermount Bathroom Sink</h3></div>
        <div class="card-grid">
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BathroomSink/ls-c6m-black-undermount-ceramic-sink.jpg" alt="LS-C6M" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-C6M Rectangular Porcelain Sink White</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BathroomSink/ls-c6mbl-white-undermount-ceramic-sink.jpg" alt="LS-C6MBL" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-C6MBL Rectangular Ceramic Sink Black</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BathroomSink/LS-C7M-white-undermount-ceramic-sink.jpg" alt="LS-C7M" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-C7M Rectangular Ceramic Sink Bisque</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BathroomSink/ls-c1-white-undermount-ceramic-sink.jpg" alt="LS-C1" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-C1 Oval Ceramic Sink White</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BathroomSink/ls-c2-bisque-undermount-ceramic-sink (1).jpg" alt="LS-C2" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-C2 Oval Ceramic Sink Bisque</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BathroomSink/ls-c3-black-undermount-ceramic-sink (1).jpg" alt="LS-C3" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-C3 Oval Ceramic Sink Black</h4></div>
            </div>
        </div>
    </div>

    <!-- ═══ BAR SINK ═══ -->
    <div class="section-block" data-category="bar">
        <div class="section-header"><h2>Bar Sink</h2></div>
        <div class="section-divider"></div>

        <div class="sub-header"><h3>Undermount Bar Sink</h3></div>
        <div class="card-grid">
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BarSink/ls-18-single-bowl-bar-sink.jpg" alt="LS-18" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-18 Single Bowl SS Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BarSink/ls-26-single-bowl-bar-sink.jpg" alt="LS-26" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-26 Single Bowl SS Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BarSink/-ls-28-single-bowl-bar-sink.jpg" alt="LS-28" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-28 Single Bowl SS Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/BarSink/ls-17-single-bowl-bar-sink.jpg" alt="LS-17" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-17 Single Bowl SS Sink</h4></div>
            </div>
        </div>
    </div>

    <!-- ═══ LAUNDRY SINK ═══ -->
    <div class="section-block" data-category="laundry">
        <div class="section-header"><h2>Laundry Sink</h2></div>
        <div class="section-divider"></div>

        <div class="sub-header"><h3>Undermount Laundry Sink</h3></div>
        <div class="card-grid">
            <div class="stone-card">
                <div class="img-wrap"><img src="images/LaundrySInk/LS-48.jpg" alt="LS-48" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-48 Single Bowl SS Sink</h4></div>
            </div>
            <div class="stone-card">
                <div class="img-wrap"><img src="images/LaundrySInk/LS-H48.jpg" alt="LS-H48" onerror="this.parentElement.innerHTML='<div class=img-placeholder><svg width=32 height=32 viewBox=&quot;0 0 24 24&quot; fill=none stroke=#bbb stroke-width=1><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg></div>'"></div>
                <div class="stone-info"><h4>LS-H48 Handmade Single Bowl SS Sink</h4></div>
            </div>
        </div>
    </div>

</div>

<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            document.querySelectorAll('.section-block').forEach(section => {
                if (filter === 'all' || section.dataset.category === filter) {
                    section.classList.remove('hidden');
                    section.style.animation = 'none';
                    section.offsetHeight;
                    section.style.animation = '';
                } else {
                    section.classList.add('hidden');
                }
            });
        });
    });
</script>

</body>
</html>