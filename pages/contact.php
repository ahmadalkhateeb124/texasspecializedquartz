<?php
$mapEmbed = setting('business_map_embed');
$mapLink  = setting('business_map_link', 'https://maps.app.goo.gl/bwV6BKwE9fJP1FbF7');
$bizPhone = setting('business_phone', '+1 (469) 814-0555');
$bizPhoneTel = preg_replace('/[^+\d]/', '', $bizPhone) ?: '+14698140555';
$bizEmail = setting('business_email', 'Cs@TexasSpecializedQuartz.com');
$bizAddr  = setting('business_address', '10830 Composite Dr, Dallas, TX 75220');
?>

<header class="page-header" data-background="images/Granit-Img/ccccc.png">
    <div class="container ee">
        <h1>Elevate Your Space — Get Your <strong>Free Consultation</strong> Today</h1>
        <p class="page-subtitle">Tell us about your project and our team will reach out with material ideas, pricing, and a timeline.</p>
    </div>
</header>

<section class="contact">
    <div class="container">
        <div class="row align-items-stretch contact-split">

            <!-- ── Sidebar ─────────────────────────────────────── -->
            <div class="col-lg-6 order-lg-1 order-1">
                <div class="contact-side-panel">
                    <div class="contact-side-eyebrow">Get in touch</div>
                    <h3 class="contact-side-title">Let's create something <em>timeless</em>.</h3>
                    <p class="contact-side-text">
                        Whether it's a kitchen remodel, bathroom upgrade, or a custom commercial install — share a few details and we'll follow up within one business day.
                    </p>

                    <ul class="contact-side-list">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <a href="<?= htmlspecialchars($mapLink) ?>" target="_blank" rel="noopener">
                                <?= htmlspecialchars($bizAddr) ?>
                            </a>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <a href="tel:<?= htmlspecialchars($bizPhoneTel) ?>"><?= htmlspecialchars($bizPhone) ?></a>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?= htmlspecialchars($bizEmail) ?>"><?= htmlspecialchars($bizEmail) ?></a>
                        </li>
                        <li>
                            <i class="far fa-clock"></i>
                            Mon–Fri 9am–6pm · Sat 9am–3pm
                        </li>
                    </ul>

                    <?php if ($mapEmbed): ?>
                        <div class="contact-side-map">
                            <iframe src="<?= htmlspecialchars($mapEmbed) ?>"
                                    width="100%" height="280" style="border:0;"
                                    allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Texas Specialized Quartz showroom map"></iframe>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── Form ────────────────────────────────────────── -->
            <div class="col-lg-6 order-lg-2 order-2">
                <div class="contact-form">
                    <div class="contact-form-head">
                        <h4 class="contact-form-title"><span>Send</span> a Message</h4>
                        <small>We'll be in touch within one business day</small>
                    </div>

                    <form method="POST" id="contactForm" name="contactForm" onsubmit="event.preventDefault(); validateAndSend();">
                        <?php include __DIR__ . "/../parts/contact-spam-fields.php"; ?>

                        <div class="form-group">
                            <input type="text" name="name" id="name" autocomplete="off" required>
                            <span>Your name</span>
                        </div>

                        <div class="form-group">
                            <input type="email" name="Email" id="Email" autocomplete="off" required>
                            <span>Your email</span>
                        </div>

                        <div class="form-group">
                            <input type="text" name="subject" id="subject" autocomplete="off" required>
                            <span>Subject</span>
                        </div>

                        <div class="form-group">
                            <textarea name="message" id="message" autocomplete="off" required></textarea>
                            <span>Tell us about your project</span>
                        </div>

                        <div class="form-group">
                            <button type="submit" id="submit" name="submit">Send Message</button>
                        </div>
                    </form>

                    <div class="form-group">
                        <div id="success" class="alert alert-success wow fadeInUp" style="display:none;" role="alert">
                            Your message was sent successfully! We will be in touch as soon as we can.
                        </div>
                        <div id="error" class="alert alert-danger wow fadeInUp" style="display:none;" role="alert">
                            Something went wrong, try refreshing and submitting the form again.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script type="text/javascript">
    function validateAndSend() {
        var form = document.getElementById('contactForm');
        if (!form.checkValidity()) {
            document.getElementById('error').textContent = 'Please fill all required fields correctly.';
            document.getElementById('error').style.display = 'block';
            document.getElementById('success').style.display = 'none';
            return;
        }
        var formData = new FormData(form);

        fetch('<?= $base_url ?>PHPMail/Inquiry.php', { method: 'POST', body: formData })
            .then(function (response) {
                if (!response.ok) throw new Error('Network response was not ok.');
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    window.location.href = "<?= $base_url ?>thank-you";
                    return;
                }
                document.getElementById('error').textContent = data.message || 'Something went wrong.';
                document.getElementById('error').style.display = 'block';
                document.getElementById('success').style.display = 'none';
            })
            .catch(function (error) {
                document.getElementById('error').textContent = 'There was a problem sending the request: ' + error.message;
                document.getElementById('error').style.display = 'block';
                document.getElementById('success').style.display = 'none';
            });
    }
</script>
