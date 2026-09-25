<section class="installation-contact">
    <div class="container">
        <div class="row align-items-stretch contact-split">
            <div class="col-lg-6 order-lg-1 order-1">
                <div class="contact-side-panel">
                    <div class="contact-side-eyebrow">Schedule Installation</div>
                    <h3 class="contact-side-title">Book your <em>free in-home measurement</em>.</h3>
                    <p class="contact-side-text">
                        Tell us about your project — we'll reach out to schedule digital templating, walk you through material options, and provide a written quote with no obligation.
                    </p>
                    <ul class="contact-side-list">
                        <li><i class="fas fa-map-marker-alt"></i> 
2943 Ladybird Ln, Dallas, TX 75220, United States</li>
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
                        <div class="form-group"><span>Your name</span><input type="text" name="name" id="name" required></div>
                        <div class="form-group"><span>Your email</span><input type="text" name="Email" id="Email" required></div>
                        <div class="form-group"><span>Subject</span><input type="text" name="subject" id="subject" required></div>
                        <div class="form-group"><span>Your message</span><textarea name="message" id="message" required></textarea></div>
                        <div class="form-group"><button type="submit">Send Message</button></div>
                    </form>
                    <div id="success" class="alert alert-success mt-3" style="display:none;">Sent! We'll be in touch shortly.</div>
                    <div id="error" class="alert alert-danger mt-3" style="display:none;">Something went wrong. Please try again.</div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
function validateAndSend(){
    const f=document.getElementById('contactForm');
    if(!f.checkValidity()) return;
    fetch('<?= $base_url ?>PHPMail/Inquiry.php',{method:'POST',body:new FormData(f)})
      .then(r=>r.json()).then(d=>{
        if(d.success){window.location.href="<?= $base_url ?>thank-you";return;}else{document.getElementById("error").style.display="block";}
      }).catch(()=>document.getElementById('error').style.display='block');
}
</script>
