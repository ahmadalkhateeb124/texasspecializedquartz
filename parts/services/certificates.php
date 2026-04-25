<section class="certificates">
    <div class="container">
        <!-- Contact form -->
        <div class="row align-items-stretch contact-split mt-5">
            <div class="col-lg-6 order-lg-1 order-1">
                <div class="contact-side-panel">
                    <div class="contact-side-eyebrow">Start your project</div>
                    <h3 class="contact-side-title">Ready for <em>premium stone</em>?</h3>
                    <p class="contact-side-text">
                        Share a few details about your project — we'll follow up with material ideas, timelines, and a free quote.
                    </p>
                    <ul class="contact-side-list">
                        <li><i class="fas fa-map-marker-alt"></i> 1225 W College Ave #616, Carrollton, TX 75006</li>
                        <li><i class="fas fa-phone"></i> <a href="tel:+14698140555">(469) 814-0555</a></li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:Cs@TexasSpecializedQuartz.com">Cs@TexasSpecializedQuartz.com</a></li>
                        <li><i class="far fa-clock"></i> Mon–Fri 9am–6pm · Sat 9am–3pm</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-2">
                <div class="contact-form">
                    <div class="contact-form-head">
                        <h4 class="contact-form-title"><span>Request</span> a Quote</h4>
                        <small>Free consultation · No obligation</small>
                    </div>
                    <form method="POST" id="contactForm" name="contactForm" onsubmit="event.preventDefault(); validateAndSend();">
<?php include __DIR__ . "/../contact-spam-fields.php"; ?>
                        <div class="form-group">
                            <span>Your name</span>
                            <input type="text" name="name" id="name" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <span>Your email</span>
                            <input type="text" name="Email" id="Email" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <span>Subject</span>
                            <input type="text" name="subject" id="subject" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <span>Your message</span>
                            <textarea name="message" id="message" autocomplete="off" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit">Send Message</button>
                        </div>
                    </form>
                    <div id="success" class="alert alert-success mt-3" style="display:none;">Your message was sent successfully! We'll be in touch shortly.</div>
                    <div id="error" class="alert alert-danger mt-3" style="display:none;">Something went wrong. Please try again.</div>
                </div>
            </div>
        </div>
        <script>
        function validateAndSend(){
            const form=document.getElementById('contactForm');
            if(!form.checkValidity()) return;
            const fd=new FormData(form);
            fetch('<?= $base_url ?>PHPMail/Inquiry.php',{method:'POST',body:fd})
              .then(r=>r.json()).then(d=>{
                if(d.success){window.location.href="<?= $base_url ?>thank-you";return;}else{document.getElementById("error").style.display="block";}
              }).catch(()=>{document.getElementById('error').style.display='block';});
        }
        </script>
    </div>
</section>
