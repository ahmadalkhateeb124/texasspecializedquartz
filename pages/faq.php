<?php
/**
 * pages/faq.php — public FAQ page, driven by faq_items table.
 */
require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';

$faqItems = [];
try {
    $faqItems = (new FaqRepository($pdo))->all(true);
} catch (Throwable $e) {
    $faqItems = [];
}
?>

<header class="page-header" data-background="images/Granit-Img/cccc.png" data-stellar-background-ratio="1.15">
    <div class="container ee">
        <h1>Frequently Asked <strong>Questions</strong></h1>
        <p class="page-subtitle">Get professional answers to common questions about granite, quartz, and marble countertops in North Texas</p>
    </div>
</header>

<section class="faq py-5" itemscope itemtype="https://schema.org/FAQPage">
    <div class="container">
        <h2 class="mb-4">Frequently Asked Questions About Countertops</h2>
        <p class="lead mb-5">Find answers to common questions about countertop materials, installation, maintenance, and services at Texas Specialized Quartz &amp; Granite.</p>

        <?php if (empty($faqItems)): ?>
            <div class="alert alert-info">No FAQs are available right now. Please check back soon.</div>
        <?php else: ?>
            <div class="accordion" id="faqAccordion">
                <?php foreach ($faqItems as $i => $f):
                    $idx     = $i + 1;
                    $openCls = $i === 0 ? 'show' : '';
                    $collapsedCls = $i === 0 ? '' : 'collapsed';
                ?>
                    <div class="card" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                        <div class="card-header" id="faq<?= $idx ?>">
                            <h3 itemprop="name">
                                <a class="<?= $collapsedCls ?>" data-toggle="collapse" href="#answer<?= $idx ?>"
                                    aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="answer<?= $idx ?>">
                                    <?= htmlspecialchars($f['question']) ?>
                                </a>
                            </h3>
                        </div>
                        <div id="answer<?= $idx ?>" class="collapse <?= $openCls ?>"
                            data-parent="#faqAccordion" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                <?= $f['answer'] /* trusted HTML written by admin */ ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
