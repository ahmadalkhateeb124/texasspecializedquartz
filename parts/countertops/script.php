<script type="text/javascript">
    function validateAndSend() {
        var form = document.getElementById('contactForm');
        if (form.checkValidity()) {
            var formData = new FormData(form);

            fetch('<?= $base_url ?>PHPMail/Inquiry.php', {
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
                    if (data.success) { window.location.href = "<?= $base_url ?>thank-you"; return;
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