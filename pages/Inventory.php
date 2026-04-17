
<style>
*{box-sizing:border-box;}
.stone-hero h1 em{
  font-style:normal;
  color:#999999;
  font-weight:600;
}
.stone-hero p{
  font-size:15px;
  color:#5c5650;
  max-width:680px;
  line-height:1.75;
  margin-bottom:.5rem;
}
.stone-hero .phone-link{
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin-top:1.25rem;
  font-size:14px;
  color:#5c5650;
}
.stone-hero .phone-link a{
  color:#999999;
  font-weight:600;
  text-decoration:none;
}
.stone-hero .phone-link a:hover{text-decoration:underline;}

/* ── FEATURES STRIP ── */
.features-strip{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:12px;
  margin-bottom:3rem;
}
.feat-item{
    background: rgb(111 111 111 / 0.11);
  border-radius:10px;
  padding:1rem 1.125rem;
}
.feat-item .feat-label{
  font-size:11px;
  color:#8c877f;
  text-transform:uppercase;
  letter-spacing:.6px;
  margin-bottom:5px;
}
.feat-item .feat-val{
  font-size:14px;
  font-weight:500;
  color:#1a1814;
}

/* ── SECTION HEADER ── */
.stone-section-head{
  display:flex;
  align-items:baseline;
  gap:12px;
  margin:3rem 0 1.5rem;
}
.stone-section-head h2{
  font-size:22px;
  font-weight:500;
  color:#1a1814;
  margin:0;
}
.stone-section-head .slab-count{
  font-size:13px;
  color:#8c877f;
}
.stone-divider{
  height:1px;
  background:#e8e2d9;
  margin-bottom:1.75rem;
}

/* ── SUB SECTION ── */
.stone-sub-head{
  display:flex;
  align-items:center;
  gap:10px;
  margin:2.5rem 0 1.5rem;
}
.stone-sub-head::before{
  content:'';
  width:3px;
  height:20px;
  background:#999999;
  border-radius:2px;
  flex-shrink:0;
}
.stone-sub-head h3{
  font-size:17px;
  font-weight:500;
  color:#1a1814;
  margin:0;
}

/* ── STONE CARD ── */
.stone-card{
  background:#fff;
  border:1px solid #e8e2d9;
  border-radius:12px;
  overflow:hidden;
  transition:border-color .2s, box-shadow .2s;
  height:100%;
}
.stone-card:hover{
  border-color:#c4b89a;
  box-shadow:0 4px 20px rgba(0,0,0,.07);
}
.stone-card .img-wrap{
  position:relative;
  aspect-ratio:4/3;
  background:#f7f4f0;
  overflow:hidden;
}
.stone-card .img-wrap img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
  transition:transform .4s ease;
}
.stone-card:hover .img-wrap img{
  transform:scale(1.04);
}
.stone-card .qty-badge{
  position:absolute;
  top:10px;
  right:10px;
  background:rgba(0,0,0,.52);
  color:#fff;
  font-size:11px;
  font-weight:500;
  padding:3px 9px;
  border-radius:20px;
  backdrop-filter:blur(4px);
  -webkit-backdrop-filter:blur(4px);
}
.stone-card .img-placeholder{
  width:100%;
  height:100%;
  display:flex;
  align-items:center;
  justify-content:center;
}
.stone-card .img-placeholder svg{opacity:.18;}
.stone-card .stone-info{
  padding:.875rem 1rem 1rem;
}
.stone-card .stone-info h4{
  font-size:14px;
  font-weight:600;
  color:#1a1814;
  margin:0 0 .5rem;
}
.stone-meta{
  display:flex;
  flex-wrap:wrap;
  gap:6px;
}
.stone-meta .tag{
  font-size:11px;
  color:#6b6560;
  background:#f7f4f0;
  padding:2px 9px;
  border-radius:20px;
}
.stone-meta .tag.size{
  color:#999999;
  background:#f5ede2;
}

/* ── QUARTZITE NO-IMAGE ── */
.no-img-card{
  background:#f7f4f0;
  border-radius:12px;
  border:1px solid #e8e2d9;
  aspect-ratio:4/3;
  display:flex;
  align-items:center;
  justify-content:center;
}
.no-img-card span{
  font-size:13px;
  color:#8c877f;
}

@media(max-width:767px){
  .features-strip{grid-template-columns:1fr 1fr;}
  .stone-section-head h2{font-size:18px;}
}
@media(max-width:480px){
  .features-strip{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<header class="page-header" data-background="images/Granit-Img/cccc.png" data-stellar-background-ratio="1.15">
  <div class="container ee">
    <h1>Elegance in Stone – <strong>Marble</strong>, <strong>Granite</strong> & <strong>Quartzite</strong> Perfection</h1>
  </div>
</header>

<section class="about-content">
  <div class="container">

    <!-- HERO TEXT -->
    <div class="stone-hero">
      <h1>Premium Natural Stone Slabs —<br><em>Marble</em>, <em>Granite</em> & <em>Quartzite</em></h1>
      <p>At <strong>Texas Specialized Quartz & Granite</strong>, we specialize in supplying a broad range of natural stone slabs and engineered stone surfaces for kitchen countertops, bathroom vanities, wall cladding, flooring, and commercial interiors.</p>
      <p>Our stain-resistant, scratch-resistant, and heat-tolerant surfaces make them a top choice for homeowners, designers, and contractors. With access to over <strong>+10 premium stone slabs</strong>, our experts help you find the perfect surface for your project.</p>
      <div class="phone-link">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
          <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 15a16 16 0 006 6l.61-.61a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
        </svg>
        Schedule a consultation: <a href="tel:+14698140555">(469) 814-0555</a>
      </div>
    </div>

    <!-- FEATURES STRIP -->
    <div class="features-strip mt-3">
      <div class="feat-item">
        <div class="feat-label">Material types</div>
        <div class="feat-val">Granite · Marble · Quartzite</div>
      </div>
      <div class="feat-item">
        <div class="feat-label">Inventory</div>
        <div class="feat-val">premium slabs</div>
      </div>
      <div class="feat-item">
        <div class="feat-label">Applications</div>
        <div class="feat-val">Residential & Commercial</div>
      </div>
    </div>

    <!-- ══════════ GRANITE ══════════ -->
    <div class="stone-section-head">
      <h2>All Inventory</h2>
      <span class="slab-count">+10 slabs available</span>
    </div>
    <div class="stone-divider"></div>
 <div class="row g-4">

      <div class="col-lg-4 col-md-6 mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/NegrescoGranite.jpeg" alt="Negresco Granite " loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 7 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Negresco Granite </h4>
            <div class="stone-meta">
              <span class="tag">Negresco Granite</span>
              <span class="tag size">3 CM</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/DallasWhiteGranite.jpeg" alt="Dallas White Granite " loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 7 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Dallas White Granite </h4>
            <div class="stone-meta">
              <span class="tag">Dallas White Granite </span>
             <span class="tag size">3 CM</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/SantaCeceliaGranite.jpeg" alt="Santa Cecelia Granite" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 7 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Santa Cecelia Granite</h4>
            <div class="stone-meta">
              <span class="tag">Santa Cecelia Granite</span>
             <span class="tag size">3 CM</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/TajMahalRioQuartz.jpeg" alt="Taj Mahal Rio Quartz " loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 20 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Taj Mahal Rio Quartz </h4>
            <div class="stone-meta">
              <span class="tag">Taj Mahal Rio Quartz </span>
              <span class="tag size">3 Cm</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/PerlaTajQuartz.jpeg" alt="Perla Taj Quartz" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 9 Slabs</span>
          </div>
          <div class="stone-info">
            <h4>Perla Taj Quartz</h4>
            <div class="stone-meta">
              <span class="tag">Perla Taj Quartz</span>
              <span class="tag size">3 Cm</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/CalcattaGoldQuartz.jpeg" alt="Calcatta Gold Quartz" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 8 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Calcatta Gold Quartz</h4>
            <div class="stone-meta">
              <span class="tag">Calcatta Gold Quartz</span>
              <span class="tag size">3 Cm</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/CristallotranslucentQuartz.jpeg" alt="Cristallo translucent Quartz" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 8 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Cristallo translucent Quartz</h4>
            <div class="stone-meta">
              <span class="tag">SCristallo translucent Quartz</span>
              <span class="tag size">3 Cm</span>
            </div>
          </div>
        </div>
      </div>



      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/ReflectQuartz.jpeg" alt="Reflect Quartz" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 10 Slabs</span>
          </div>
          <div class="stone-info">
            <h4>Reflect Quartz</h4>
            <div class="stone-meta">
              <span class="tag">Reflect Quartz</span>
              <span class="tag size">3 Cm </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/MarmiQuartz.jpeg" alt="Marmi Quartz" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 10 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Marmi Quartz</h4>
            <div class="stone-meta">
              <span class="tag">Marmi Quartz</span>
              <span class="tag size">3 Cm</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/RockyQuartz.jpeg" alt="Rocky Quartz " loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 9 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Rocky Quartz </h4>
            <div class="stone-meta">
              <span class="tag">Rocky Quartz </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6  mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="images/InventoryImag/GubbioQuartz.jpeg" alt="Gubbio Quartz" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
            <span class="qty-badge">Availability 20 slabs</span>
          </div>
          <div class="stone-info">
            <h4>Gubbio Quartz</h4>
            <div class="stone-meta">
              <span class="tag">Gubbio Quartz</span>
              <span class="tag size">3 Cm</span>
            </div>
          </div>
        </div>
      </div><!-- end granite row -->


     <div class="col-lg-4 col-md-6  mb-3">
     <div class="stone-card">
         <div class="img-wrap">
             <img src="images/InventoryImag/UltraQuartz.jpeg" alt="Gubbio Quartz" loading="lazy"
                  onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
             <span class="qty-badge">Availability 8 slabs</span>
         </div>
         <div class="stone-info">
             <h4>Ultra Quartz</h4>
             <div class="stone-meta">
                 <span class="tag">Ultra Quartz  </span>
                 <span class="tag size">3 Cm</span>
             </div>
         </div>
     </div>

         </div >


     <div class="col-lg-4 col-md-6  mb-3">
     <div class="stone-card">
         <div class="img-wrap">
             <img src="images/InventoryImag/Purewhitequartz.jpeg" alt="Gubbio Quartz" loading="lazy"
                  onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><svg width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#bbb\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
             <span class="qty-badge">Availability 10 slabs</span>
         </div>
         <div class="stone-info">
             <h4>Pure white quartz</h4>
             <div class="stone-meta">
                 <span class="tag">Pure white quartz</span>
                 <span class="tag size">3 Cm</span>
             </div>
         </div>
     </div>

         </div>


 </div>
</section>

</body>
</html>