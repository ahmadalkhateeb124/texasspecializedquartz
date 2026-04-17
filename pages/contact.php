<style>
    .form-group input,
    textarea {
        background: #E3E3E3;
    }
</style>
<header class="page-header" data-background="images/Granit-Img/ccccc.png" data-stellar-background-ratio="1.15">
    <div class="container ee">
        <h1>Elevate Your Space – Get Your <strong>Free Consultation</strong> Today</h1>
        <p class="page-subtitle">Contact our expert team for a personalized countertop consultation and quote</p>
    </div>
    <!-- end container -->
</header>
<!-- end page-header -->
<section class="contact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 wow fadeInUp"> <b>07</b>
                <h4><span>Contact</span> Us</h4>
                <small>Contact us today </small>
            </div>
            <!-- end col-6 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <address>
                    <strong>Visit Us</strong>
                    <a href="https://maps.app.goo.gl/bwV6BKwE9fJP1FbF7" target="_blank">
                        10830 Composite Dr. Dallas, Tx 75220
                    </a>

                </address>
            </div>
            <!-- end col-3 -->
            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <address>
                    <strong>Say Hello</strong>
                    <p><a href="mailto:Cs@TexasSpecializedQuartz.com">Cs@TexasSpecializedQuartz.com</a></p></br>

                    <a href="tel:+1 469-814-0555">+1 (469) 814-0555</a>

                    </p>
                </address>
            </div>
            <!-- end col-3 -->
        </div>
        <!-- end row -->
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="map">
                    <div class="pattern-bg" data-stellar-ratio="1.03"></div>
                    <!-- end pattern-bg -->
                    <div class="holder" data-stellar-ratio="1.07">
                   
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3350.793193050236!2d-96.89821232346755!3d32.87719077362204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e9da255f55de5%3A0xf000f0f512c61178!2sTexas%20Specialized%20Quartz%20%26%20Granite!5e0!3m2!1sen!2sjo!4v1776074815582!5m2!1sen!2sjo" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <!-- end holder -->
                </div>
                <!-- end map -->
            </div>
            <!-- end col-6 -->
            <div class="col-lg-6">
                <div class="contact-form">
                    <form method="POST" id="contactForm" name="contactForm" onsubmit="event.preventDefault(); validateAndSend();">
                        <div class="form-group">
                            <span>Your name</span>
                            <input type="text" name="name" id="name" autocomplete="off" required>

                        </div>

                        <div class="form-group">
                            <span>Your-Email</span>
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
                            <button type="submit" id="submit" name="submit">Submit</button>
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
                <!-- end contact-form -->
            </div>

            <!-- end col-6 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<script type="text/javascript">
    function validateAndSend() {
        var form = document.getElementById('contactForm');
        if (form.checkValidity()) {
            var formData = new FormData(form);

            fetch('../PHPMail/Inquiry.php', { // <-- تأكد من المسار هنا
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('success').style.display = 'block';
                        document.getElementById('error').style.display = 'none';
                        console.log(data.message);
                        form.reset();
                    } else {
                        document.getElementById('error').textContent = data.message;
                        document.getElementById('error').style.display = 'block';
                        document.getElementById('success').style.display = 'none';
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    document.getElementById('error').textContent = 'There was a problem sending the request: ' + error.message;
                    document.getElementById('error').style.display = 'block';
                    document.getElementById('success').style.display = 'none';
                    console.error('Fetch error:', error);
                });
        } else {
            document.getElementById('error').textContent = 'Please fill all required fields correctly.';
            document.getElementById('error').style.display = 'block';
            document.getElementById('success').style.display = 'none';
        }
    }
</script>