<?php

/**
 * Generate band email signatures + public preview page.
 *
 * Usage: php scripts/generate-email-signatures.php
 */

$members = [
    ['slug' => 'oleg', 'name' => 'Oleg', 'role' => 'Singer'],
    ['slug' => 'maxim', 'name' => 'Maxim', 'role' => 'Lead Guitar'],
    ['slug' => 'yevgeny', 'name' => 'Yevgeny', 'role' => 'Bass Guitar'],
    ['slug' => 'katerina', 'name' => 'Katerina', 'role' => 'Singer'],
    ['slug' => 'max', 'name' => 'Max', 'role' => 'Singer'],
    ['slug' => 'olena', 'name' => 'Olena', 'role' => 'Rhythm Guitar'],
    ['slug' => 'aleksandr', 'name' => 'Aleksandr', 'role' => 'Drums'],
];

// Absolute URL + cache-buster — required for mail clients (relative /images/... breaks in Gmail/Mail).
$logo = 'https://www.poprockavenue.nl/images/logo/logo-mark-v1-signature.png?v=20261005';
$email = 'avenuepoprock@gmail.com';
$links = [
    ['Website', 'https://www.poprockavenue.nl/'],
    ['Instagram', 'https://instagram.com/poprockavenue'],
    ['TikTok', 'https://www.tiktok.com/@poprock.avenue'],
    ['YouTube', 'https://www.youtube.com/@Pop-RockAvenue'],
];

$root = dirname(__DIR__);
$dir = $root.'/resources/email-signatures';

if (! is_dir($dir)) {
    mkdir($dir, 0755, true);
}

function linksHtml(array $links, string $linkColor, string $sepColor): string
{
    $parts = [];
    foreach ($links as $i => [$label, $href]) {
        if ($i > 0) {
            $parts[] = '<span style="color:'.$sepColor.';">&nbsp;&nbsp;·&nbsp;&nbsp;</span>';
        }
        $parts[] = '<a href="'.htmlspecialchars($href, ENT_QUOTES).'" style="color:'.$linkColor.';text-decoration:none;">'.$label.'</a>';
    }

    return implode('', $parts);
}

function signatureHtml(array $member, string $variant, string $logo, string $email, array $links): string
{
    $isDark = $variant === 'dark';
    $bg = $isDark ? '#0B0C12' : '#FFFFFF';
    $border = $isDark ? '#2A2D3A' : '#E2E8F0';
    $nameColor = $isDark ? '#F8FAFC' : '#0F172A';
    $roleColor = $isDark ? '#22D3EE' : '#0891B2';
    $muted = $isDark ? '#94A3B8' : '#64748B';
    $rule = $isDark ? '#1E2230' : '#E2E8F0';
    $mailColor = $isDark ? '#A78BFA' : '#7C3AED';
    $linkColor = $isDark ? '#22D3EE' : '#0891B2';
    $sepColor = $isDark ? '#475569' : '#CBD5E1';

    $name = htmlspecialchars($member['name'], ENT_QUOTES);
    $role = htmlspecialchars(strtoupper($member['role']), ENT_QUOTES);
    $emailSafe = htmlspecialchars($email, ENT_QUOTES);
    $linksHtml = linksHtml($links, $linkColor, $sepColor);

    return <<<HTML
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;max-width:440px;">
    <tr>
        <td style="padding:0;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" bgcolor="{$bg}" width="100%" style="border-collapse:collapse;background-color:{$bg};border:1px solid {$border};max-width:440px;">
                <tr>
                    <td style="padding:0;font-size:0;line-height:0;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
                            <tr>
                                <td width="50%" height="3" bgcolor="#7C3AED" style="background-color:#7C3AED;font-size:0;line-height:0;height:3px;">&nbsp;</td>
                                <td width="50%" height="3" bgcolor="#22D3EE" style="background-color:#22D3EE;font-size:0;line-height:0;height:3px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 18px 14px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                            <tr>
                                <td style="vertical-align:middle;padding:0 14px 0 0;">
                                    <a href="https://www.poprockavenue.nl/" style="text-decoration:none;">
                                        <img src="{$logo}" alt="Pop Rock Avenue" width="64" height="64" style="display:block;border:0;outline:none;width:64px;height:64px;">
                                    </a>
                                </td>
                                <td width="2" bgcolor="#7C3AED" style="width:2px;background-color:#7C3AED;font-size:0;line-height:0;">&nbsp;</td>
                                <td style="vertical-align:middle;padding:0 0 0 14px;">
                                    <p style="margin:0 0 1px;font-size:17px;font-weight:bold;line-height:1.25;letter-spacing:0.02em;color:{$nameColor};">{$name}</p>
                                    <p style="margin:0 0 2px;font-size:11px;font-weight:bold;line-height:1.35;letter-spacing:0.14em;text-transform:uppercase;color:{$roleColor};">{$role}</p>
                                    <p style="margin:0;font-size:12px;line-height:1.4;color:{$muted};">POP/ROCK AVENUE · Netherlands</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 18px 16px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;border-top:1px solid {$rule};">
                            <tr>
                                <td style="padding:12px 0 0;">
                                    <p style="margin:0 0 8px;font-size:13px;line-height:1.4;">
                                        <a href="mailto:{$emailSafe}" style="color:{$mailColor};text-decoration:none;">{$emailSafe}</a>
                                    </p>
                                    <p style="margin:0;font-size:12px;line-height:1.7;">{$linksHtml}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
HTML;
}

$payloads = [];

foreach ($members as $member) {
    foreach (['dark', 'light'] as $variant) {
        $html = signatureHtml($member, $variant, $logo, $email, $links);
        $path = "{$dir}/{$member['slug']}-{$variant}.html";
        file_put_contents($path, "<!-- POP/ROCK AVENUE — {$member['name']} ({$member['role']}) · ".strtoupper($variant)." -->\n{$html}\n");
        $payloads[$member['slug']][$variant] = $html;
        echo "wrote {$path}\n";
    }
}

// Preview must also use absolute logo URLs so a manual select-copy still works in mail.
$toLocal = fn (string $html) => $html;

$cards = '';
$jsPayloads = [];
$nav = '';

foreach ($members as $member) {
    $slug = $member['slug'];
    $name = htmlspecialchars($member['name']);
    $role = htmlspecialchars($member['role']);
    $nav .= '<a href="#'.$slug.'">'.$name.'</a>';
    $jsPayloads["{$slug}-dark"] = $payloads[$slug]['dark'];
    $jsPayloads["{$slug}-light"] = $payloads[$slug]['light'];
    $darkLocal = $toLocal($payloads[$slug]['dark']);
    $lightLocal = $toLocal($payloads[$slug]['light']);

    $cards .= <<<HTML
  <section class="member" id="{$slug}">
    <div class="member-head"><h2>{$name}</h2><p class="role">{$role}</p></div>
    <div class="variants">
      <div class="card">
        <div class="card-head"><span>Dark</span>
          <button type="button" class="copy-btn" data-key="{$slug}-dark" aria-label="Copy dark signature for {$name}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            <span class="copy-label">Copy</span>
          </button>
        </div>
        <div class="stage">{$darkLocal}</div>
      </div>
      <div class="card">
        <div class="card-head"><span>Light</span>
          <button type="button" class="copy-btn" data-key="{$slug}-light" aria-label="Copy light signature for {$name}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            <span class="copy-label">Copy</span>
          </button>
        </div>
        <div class="stage">{$lightLocal}</div>
      </div>
    </div>
  </section>
HTML;
}

$payloadJson = json_encode($jsPayloads, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$preview = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Email signatures — POP/ROCK AVENUE</title>
<style>
  body { margin:0; font-family:Arial,Helvetica,sans-serif; background:#F8FAFC; color:#0F172A; }
  .wrap { max-width:980px; margin:0 auto; padding:28px 20px 56px; }
  .intro { padding:16px 18px; background:#FFFFFF; border:1px solid #E2E8F0; font-size:13px; line-height:1.5; color:#64748B; margin-bottom:18px; }
  .intro strong { color:#0F172A; }
  .nav { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:28px; }
  .nav a { font-size:12px; text-decoration:none; color:#0F172A; padding:8px 12px; background:#FFFFFF; border:1px solid #E2E8F0; }
  .nav a:hover { border-color:#7C3AED; color:#7C3AED; }
  .member { margin-bottom:28px; }
  .member-head { margin-bottom:12px; }
  .member-head h2 { margin:0 0 2px; font-size:22px; }
  .member-head .role { margin:0; font-size:13px; color:#64748B; letter-spacing:.04em; text-transform:uppercase; }
  .variants { display:grid; gap:16px; }
  @media (min-width:860px) { .variants { grid-template-columns:1fr 1fr; } }
  .card { background:#FFFFFF; border:1px solid #E2E8F0; padding:16px; }
  .card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px; font-size:12px; letter-spacing:.12em; text-transform:uppercase; color:#64748B; }
  .stage { padding:16px; background:#F1F5F9; border:1px solid #E2E8F0; display:flex; justify-content:center; }
  .copy-btn { display:inline-flex; align-items:center; gap:8px; padding:9px 12px; border:0; cursor:pointer; background:linear-gradient(135deg,#7C3AED,#22D3EE); color:#0B0C12; font:600 11px/1 Arial,Helvetica,sans-serif; letter-spacing:.04em; text-transform:uppercase; }
  .copy-btn:hover { filter:brightness(1.05); }
  .copy-btn.is-ok { background:#22D3EE; }
  .copy-btn svg { width:14px; height:14px; }
</style>
</head>
<body>
<div class="wrap">
  <div class="intro">
    Band email signatures — pick <strong>Dark</strong> (safer on any theme) or <strong>Light</strong>, then click <strong>Copy</strong> and paste into Gmail / Outlook / Apple Mail.
    Contact email on all cards: <strong>avenuepoprock@gmail.com</strong>.
    Use the <strong>Copy</strong> button (not select-all) so the logo URL stays absolute — otherwise the image breaks in mail.
  </div>
  <nav class="nav">{$nav}</nav>
  {$cards}
</div>
<script>
(function () {
  var payloads = {$payloadJson};
  document.querySelectorAll('.copy-btn').forEach(function (btn) {
    btn.addEventListener('click', async function () {
      var html = payloads[btn.getAttribute('data-key')];
      var label = btn.querySelector('.copy-label');
      try {
        if (navigator.clipboard && window.ClipboardItem) {
          await navigator.clipboard.write([new ClipboardItem({
            'text/html': new Blob([html], { type: 'text/html' }),
            'text/plain': new Blob([html], { type: 'text/plain' })
          })]);
        } else {
          var box = document.createElement('div');
          box.innerHTML = html;
          box.style.position = 'fixed';
          box.style.left = '-9999px';
          document.body.appendChild(box);
          var range = document.createRange();
          range.selectNodeContents(box);
          var sel = window.getSelection();
          sel.removeAllRanges();
          sel.addRange(range);
          document.execCommand('copy');
          sel.removeAllRanges();
          document.body.removeChild(box);
        }
        btn.classList.add('is-ok');
        label.textContent = 'Copied';
        setTimeout(function () { btn.classList.remove('is-ok'); label.textContent = 'Copy'; }, 1600);
      } catch (e) {
        label.textContent = 'Failed';
        setTimeout(function () { label.textContent = 'Copy'; }, 1600);
      }
    });
  });
})();
</script>
</body>
</html>
HTML;

file_put_contents($root.'/public/email-signature-preview.html', $preview);
echo "preview written\n";
