<?php $pageTitle = 'Home'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($pageTitle) ?> — KHOTWA</title><link rel="stylesheet" href="css/home-template.css"></head><body id="top"><?php require FRONT_INC . 'public_nav.php'; ?>
<main id="main"><div class="page-flash"><?php render_flashes(); ?></div>
<section class="hero2"><div class="container hero2-grid">
 <div><span class="badge">A space without judgment</span>
 <h1>Every journey begins with <em class="serif">one step.</em></h1>
 <p class="lead" style="max-width:480px">You don't have to figure everything out alone. Discover a space where understanding, guidance and human support come together.</p>
 <div class="actions"><a class="btn btn-primary" href="#next">Take Your First Step <span class="steps" aria-hidden="true"><i></i><i></i><i></i></span></a><a class="btn" href="#explore">Explore KHOTWA</a></div>
 <p class="slogan ar" lang="ar">كل خطوة تصنع الفرق</p></div>
 <div class="hero2-art"><svg viewBox="0 0 520 520" role="img" aria-label="A dotted path of footprints climbing a hill toward a rising sun, with a small plant at its start">
  <defs><filter id="rough"><feTurbulence baseFrequency=".03" numOctaves="2" seed="4"/><feDisplacementMap in="SourceGraphic" scale="5"/></filter>
  <g id="step"><ellipse cx="0" cy="0" rx="9" ry="14" /><ellipse cx="0" cy="22" rx="6" ry="8"/></g></defs>
  <g filter="url(#rough)">
   <path d="M20 500C120 470 180 520 300 470S470 440 520 400 640 380V520H20Z" fill="#8EB7A0"/>
   <path d="M60 520C140 400 260 430 330 330S330 190 420 140" fill="none" stroke="#E9DCC8" stroke-width="64" stroke-linecap="round"/>
   <circle class="sun" cx="430" cy="82" r="62" fill="#D79B69"/><circle cx="430" cy="82" r="86" fill="none" stroke="#F2B9A7" stroke-width="3" stroke-dasharray="2 12" stroke-linecap="round"/>
   <path d="M60 520C140 400 260 430 330 330S330 190 420 140" fill="none" stroke="#173D36" stroke-width="3" class="dash"/>
   <g fill="#173D36">
    <use href="#step" class="fp" style="--i:0" transform="translate(95 462) rotate(-35)"/><use href="#step" class="fp" style="--i:1" transform="translate(160 440) rotate(-20)"/>
    <use href="#step" class="fp" style="--i:2" transform="translate(232 428) rotate(-10)"/><use href="#step" class="fp" style="--i:3" transform="translate(300 380) rotate(20)"/>
    <use href="#step" class="fp" style="--i:4" transform="translate(340 312) rotate(5)"/><use href="#step" class="fp" style="--i:5" transform="translate(352 240) rotate(-15)"/>
    <use href="#step" class="fp" style="--i:6" transform="translate(395 170) rotate(-30)"/></g>
   <path d="M40 470v-60M40 440c-28-2-34-26-28-38 26 2 32 18 28 38zM40 430c26-2 36-22 30-36-26 0-34 16-30 36z" fill="#173D36" stroke="#173D36" stroke-width="3" stroke-linecap="round"/>
   <path d="M120 120l4 10 10 4-10 4-4 10-4-10-10-4 10-4zM250 60l3 8 8 3-8 3-3 8-3-8-8-3 8-3z" fill="#173D36" class="twinkle"/>
  </g></svg></div></div></section>
<div class="sep" aria-hidden="true"></div>

<section class="intro dark" id="message"><div class="container intro-grid">
 <h2 class="statement reveal">Sometimes, the hardest step is <b>the first one.</b></h2>
 <div class="reveal"><p>Not everyone knows where to ask for help. Not everyone feels comfortable talking about what they are going through. KHOTWA exists to make information, prevention and support easier to reach.</p>
 <ul class="trail-list"><li style="--k:0">Understand where you are</li><li style="--k:1">Find people and places that can help</li><li style="--k:2">Move forward, at your own pace</li></ul></div></div></section>

<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
 <symbol id="i-compass" viewBox="0 0 32 32"><circle cx="16" cy="16" r="12" fill="none" stroke="currentColor" stroke-width="2.5"/><path d="M21 11l-3 8-8 3 3-8z" fill="currentColor"/></symbol>
 <symbol id="i-cross" viewBox="0 0 32 32"><path d="M12 5h8v7h7v8h-7v7h-8v-7H5v-8h7z" fill="currentColor"/></symbol>
 <symbol id="i-flag" viewBox="0 0 32 32"><path d="M8 28V5m0 2h16l-4 6 4 6H8" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/></symbol>
 <symbol id="i-chat" viewBox="0 0 32 32"><path d="M5 8h22v14H15l-6 5v-5H5z" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></symbol>
 <symbol id="i-book" viewBox="0 0 32 32"><path d="M16 8c-3-3-8-3-11-2v18c3-1 8-1 11 2 3-3 8-3 11-2V6c-3-1-8-1-11 2zM16 8v18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></symbol>
 <symbol id="i-mega" viewBox="0 0 32 32"><path d="M5 13h6l11-6v18l-11-6H5zM11 19l2 7" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></symbol></defs></svg>
<section class="section" id="explore"><div class="container">
 <div class="section-head reveal"><h2>Explore <em class="serif">KHOTWA</em></h2><p>Discover everything KHOTWA offers to help you learn, find support and move at your own pace.</p></div>
 <div class="svc-grid">
  <article class="svc sv-a a-sage reveal"><div class="ico"><svg aria-hidden="true"><use href="#i-compass"/></svg></div><h3>Smart Orientation</h3><p>Answer a few guided questions to make sense of your situation and see which kind of support may suit you.</p><a class="link" href="index.php?action=info&amp;page=smart-orientation">Find your direction</a></article>
  <article class="svc sv-b a-sky reveal"><div class="ico"><svg aria-hidden="true"><use href="#i-cross"/></svg></div><h3>Medical Network</h3><p>Find health professionals and support organisations, so you know where to turn when you are ready.</p><a class="link" href="index.php?action=info&amp;page=medical-network">Find support</a></article>
  <article class="svc sv-c reveal"><div class="ico"><svg aria-hidden="true"><use href="#i-flag"/></svg></div><h3>Recovery Journey</h3><p>Set goals that feel realistic, follow your progress and give credit to every step, however small.</p><a class="link" href="index.php?action=info&amp;page=recovery-journey">Plan your steps</a></article>
  <article class="svc sv-d a-pink reveal"><div class="ico"><svg aria-hidden="true"><use href="#i-chat"/></svg></div><h3>Emotional &amp; Social Support</h3><p>A place to listen, to share and to be heard, because nobody should have to carry things alone.</p><a class="link" href="index.php?action=info&amp;page=emotional-support">Find a listening space</a></article>
  <article class="svc sv-e a-lav reveal"><div class="ico"><svg aria-hidden="true"><use href="#i-book"/></svg></div><h3>Prevention &amp; Education</h3><p>Read, learn and test your knowledge with clear resources that explain addiction without judgment.</p><a class="link" href="index.php?action=info&amp;page=prevention">Start learning</a></article>
  <article class="svc sv-f a-peach reveal"><div class="ico"><svg aria-hidden="true"><use href="#i-mega"/></svg></div><h3>Awareness &amp; Community</h3><p>Follow awareness campaigns and events, and join collective initiatives that make a difference.</p><a class="link" href="index.php?action=info&amp;page=community">Join in</a></article>
 </div></div></section>

<section class="section" id="next" style="background:var(--beige)"><div class="container"><div class="section-head" style="margin-bottom:0"><h2>What's your <em class="serif">next step?</em></h2><p>There is no wrong answer. Pick what feels closest to where you are today.</p></div>
 <div class="pick" role="group" aria-label="Choose your next step"><button type="button" data-choice="understand" aria-pressed="false">I want to understand.</button><button type="button" data-choice="talk" aria-pressed="false">I need someone to talk to.</button><button type="button" data-choice="help" aria-pressed="false">I want to help someone.</button></div>
 <div class="next-out" id="next-out" aria-live="polite" hidden></div></div></section>

<section class="section"><div class="container">
 <div class="section-head reveal"><span class="badge">Only on KHOTWA</span><h2 style="margin-top:1rem">Three experiences, <em class="serif">made for you</em></h2></div>
 <div class="xp xp-capsule reveal"><div><h2>Ma Capsule du Futur</h2><p><strong>My Future Capsule</strong></p><blockquote>Write today what you want your future self to remember.</blockquote><span class="badge" style="background:#fff">Front-end prototype</span><p style="margin:.8rem 0 1.4rem;font-size:.95rem">For now, your capsule stays on this device only.</p><button class="btn btn-primary" data-open="capsule">Write my capsule</button></div>
  <svg viewBox="0 0 300 220" aria-hidden="true"><g transform="rotate(-6 150 120)"><rect x="30" y="50" width="240" height="150" rx="14" fill="#fff"/><path d="M30 64l120 78 120-78" fill="none" stroke="#173D36" stroke-width="5" stroke-linejoin="round"/><circle cx="150" cy="142" r="22" fill="#D79B69"/><path d="M150 130v12l8 5" stroke="#173D36" stroke-width="4" fill="none" stroke-linecap="round"/></g><path d="M10 20c60-30 120 10 180-10" fill="none" stroke="#173D36" stroke-width="3" stroke-dasharray="1 10" stroke-linecap="round"/><circle cx="196" cy="8" r="6" fill="#173D36"/></svg></div></div>
<div class="xp xp-circle" style="margin-top:var(--s4);margin-inline:calc(50% - 50vw);width:100vw"><div class="container wrap reveal">
  <svg viewBox="0 0 320 320" aria-hidden="true"><circle cx="160" cy="160" r="110" fill="none" stroke="#D79B69" stroke-width="3" stroke-dasharray="1 10" stroke-linecap="round"/><circle cx="160" cy="160" r="60" fill="none" stroke="#8EB7A0" stroke-width="2" opacity=".6"/><g><circle cx="160" cy="50" r="22" fill="#E9BFD2"/><circle cx="255" cy="105" r="18" fill="#D9C9F3"/><circle cx="255" cy="215" r="24" fill="#F2B9A7"/><circle cx="160" cy="270" r="19" fill="#DCE9F5"/><circle cx="65" cy="215" r="23" fill="#8EB7A0"/><circle cx="65" cy="105" r="18" fill="#D79B69"/></g><circle cx="160" cy="160" r="16" fill="#F7F5EF"/></svg>
  <div><h2>Le Cercle Invisible</h2><p><strong>The Invisible Circle</strong></p><blockquote>Sometimes, knowing you're not alone changes everything.</blockquote><a class="btn btn-gold" href="index.php?action=info&amp;page=emotional-support">Discover the circle <span class="steps" aria-hidden="true"><i></i><i></i><i></i></span></a></div></div></div>
<div class="xp xp-future reveal"><h2>Mon Futur Moi</h2><p><strong>My Future Self</strong></p><blockquote style="max-width:36ch">Imagine who you want to become. Start building that future today.</blockquote>
  <div class="miles"><div style="--k:0"><b>Today</b>One small step</div><div style="--k:1"><b>Next month</b>A habit that holds</div><div style="--k:2"><b>One day</b>The change you imagined</div></div>
  <p style="margin-top:2rem"><a class="btn btn-primary" href="index.php?action=info&amp;page=recovery-journey">Imagine my path</a></p></div>
</div></section>

<section class="section" id="how"><div class="container">
 <div class="section-head reveal"><h2>How KHOTWA <em class="serif">works</em></h2></div>
 <div class="journey2"><svg viewBox="0 0 1000 140" preserveAspectRatio="none" aria-hidden="true"><path d="M125 40C250 40 250 92 375 92S500 40 625 40 750 92 875 92" fill="none" stroke="#D79B69" stroke-width="5" stroke-dasharray="1 14" stroke-linecap="round" vector-effect="non-scaling-stroke"/></svg>
  <div class="j-step a-sage reveal"><div class="n">1</div><h3>Discover</h3><p>Get to know the platform and what it offers.</p></div>
  <div class="j-step a-lav reveal"><div class="n">2</div><h3>Understand</h3><p>Reflect on your situation and name what you need.</p></div>
  <div class="j-step a-peach reveal"><div class="n">3</div><h3>Connect</h3><p>Find support and resources that fit you.</p></div>
  <div class="j-step a-gold reveal"><div class="n">4</div><h3>Move Forward</h3><p>Set goals and keep going, one step at a time.</p></div></div></div></section>

<section class="section" style="background:#fff"><div class="container">
 <div class="section-head reveal"><span class="badge">Prevention &amp; awareness</span><h2 style="margin-top:1rem">Learn, question, <em class="serif">share</em></h2></div>
 <div class="mag"><div>
  <article class="feat reveal"><div><span class="kicker">Feature</span><h3>Noticing the first signs</h3><p>A habit rarely becomes a problem overnight. This guide looks at the small changes worth noticing in yourself or in someone close to you, and what to do next.</p><a class="link" href="index.php?action=info&amp;page=prevention">Read the guide</a></div>
   <div class="art"><svg viewBox="0 0 220 220" aria-hidden="true"><path d="M110 200v-90" stroke="#173D36" stroke-width="5" stroke-linecap="round"/><path d="M110 150c-40-4-50-38-42-56 38 2 48 26 42 56zM110 120c36-4 52-34 44-56-36 2-50 26-44 56z" fill="#8EB7A0" stroke="#173D36" stroke-width="4" stroke-linejoin="round"/><path d="M20 205c60-12 120-12 180 0" stroke="#D79B69" stroke-width="4" stroke-dasharray="1 10" stroke-linecap="round" fill="none"/></svg></div></article>
  <article class="row reveal"><span class="kicker">Awareness</span><h3>Talking without judgment</h3><p>Simple ways to open a conversation with a friend who may be struggling.</p></article>
  <article class="row reveal"><span class="kicker">Understanding</span><h3>Why asking for help is hard</h3><p>Shame, fear and silence: what holds people back, and how to ease it.</p></article></div>
  <aside class="side"><div class="quiz reveal" id="quiz"><span class="badge">Quick quiz</span><h3>Myth or fact: “Asking for help is a sign of weakness.”</h3><div class="opts"><button class="btn btn-light" data-ans="myth">Myth</button><button class="btn btn-light" data-ans="fact">Fact</button></div><p class="fb" aria-live="polite"></p></div>
   <div class="reveal"><span class="kicker">Resources</span><ul class="res"><li><a href="index.php?action=info&amp;page=prevention">How to talk to a friend</a><span aria-hidden="true">→</span></li><li><a href="index.php?action=info&amp;page=prevention">Know your limits: a self-check</a><span aria-hidden="true">→</span></li><li><a href="index.php?action=info&amp;page=medical-network">Where to find professional help</a><span aria-hidden="true">→</span></li></ul></div></aside></div>
 <div class="campaign reveal"><div><span class="kicker" style="color:var(--forest)">Awareness campaigns</span><h3>Small actions, shared widely</h3></div><a class="btn btn-primary" href="index.php?action=info&amp;page=community">See what's happening</a></div>
</div></section>

<section class="cta2"><svg viewBox="0 0 420 110" aria-hidden="true"><path d="M10 100C90 100 110 60 190 60S300 20 400 14" fill="none" stroke="#D79B69" stroke-width="4" stroke-dasharray="1 12" stroke-linecap="round"/><circle cx="30" cy="100" r="5" fill="#D79B69"/><circle cx="140" cy="72" r="7" fill="#D79B69"/><circle cx="250" cy="42" r="9" fill="#D79B69"/><circle cx="395" cy="16" r="16" fill="#F2B9A7"/></svg>
 <h2>Your first step matters.</h2><p>You don't have to figure everything out today. Start by discovering the support and resources available to you.</p>
 <a class="btn btn-gold" href="index.php?action=info&amp;page=smart-orientation">Take Your First Step <span class="steps" aria-hidden="true"><i></i><i></i><i></i></span></a></section>
<section class="section"><div class="container"><div class="xp xp-capsule"><div><h2>Tu es professionnel(le) de santé ?</h2><p>Rejoins le réseau Khotwa.</p><a class="btn btn-primary" href="index.php?action=register&amp;type=professional">Devenir partenaire</a></div></div></div></section></main>

<div class="modal" id="capsule" role="dialog" aria-modal="true" aria-labelledby="cap-t"><div class="modal-box"><button class="modal-x" aria-label="Close">✕</button>
 <h3 id="cap-t" style="margin-bottom:.5rem">Ma Capsule du Futur</h3><p class="alert" style="margin-bottom:1rem">Front-end prototype: your message is saved in this browser only and will not be delivered yet.</p>
 <form id="capsule-form" novalidate><p id="capsule-error" class="alert" role="alert" hidden></p><div class="field"><label for="msg">Your message to your future self</label><textarea class="input" id="msg" name="msg" rows="4"></textarea></div>
 <div class="field"><label for="date">Open on</label><input class="input" type="date" id="date" name="date"></div>
 <button class="btn btn-primary" type="submit">Seal my capsule</button><p class="alert alert-success" id="capsule-ok" hidden style="margin-top:1rem">Your capsule is sealed in this browser.</p></form></div></div>
<?php require FRONT_INC . 'public_footer.php'; ?><script src="js/home-template.js"></script></body></html>