<?php
/**
 * FAQ JSON-LD — pulled from faq_items.
 */
try {
    $faqItems = (new FaqRepository($pdo))->all(true);
} catch (Throwable $e) { return; }
if (!$faqItems) return;

$qas = [];
foreach ($faqItems as $f) {
    $q = trim($f['question']);
    $a = trim(strip_tags($f['answer']));
    $a = preg_replace('/\s+/', ' ', $a);
    if ($q === '' || $a === '') continue;
    if (mb_strlen($a) > 900) $a = mb_substr($a, 0, 900) . '…';
    $qas[] = [
        '@type'          => 'Question',
        'name'           => $q,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
    ];
}

if (!$qas) return;

$schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $qas,
];
?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
