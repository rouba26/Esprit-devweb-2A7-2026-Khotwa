(function(){
const hd=document.getElementById('hd'),bg=hd.querySelector('.burger');
addEventListener('scroll',()=>hd.classList.toggle('scrolled',scrollY>10),{passive:true});
const setMenu=o=>{hd.classList.toggle('open',o);bg.setAttribute('aria-expanded',o);bg.setAttribute('aria-label',o?'Close menu':'Open menu');bg.textContent=o?'✕':'☰'};
bg.onclick=()=>setMenu(!hd.classList.contains('open'));
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}}),{threshold:.15});
document.querySelectorAll('.reveal,.journey').forEach(el=>io.observe(el));
document.addEventListener('click',e=>{
const a=e.target.closest('a[href]');
if(a&&!a.target&&!a.getAttribute('href').includes('#')&&a.origin===location.origin&&!matchMedia('(prefers-reduced-motion:reduce)').matches){e.preventDefault();document.body.classList.add('leaving');setTimeout(()=>location.href=a.href,250);return}
if(a&&hd.classList.contains('open'))setMenu(false);
const o=e.target.closest('[data-open]');if(o){e.preventDefault();const m=document.getElementById(o.dataset.open);m.classList.add('open');m.querySelector('textarea,input,button').focus();return}
if(e.target.closest('.modal-x')||e.target.classList.contains('modal'))e.target.closest('.modal').classList.remove('open')});
addEventListener('keydown',e=>{if(e.key==='Escape'){document.querySelectorAll('.modal.open').forEach(m=>m.classList.remove('open'));setMenu(false)}});
addEventListener('pageshow',()=>document.body.classList.remove('leaving'));

const NS={understand:['Understanding is a good place to start. A few guided questions can help you put words on what you are going through.','smart-orientation','Go to Smart Orientation'],
talk:['You deserve to be heard. Emotional & Social Support is a place to listen, share and feel less alone, at your own pace.','emotional-support','Go to Emotional & Social Support'],
help:['Supporting someone starts with listening without judgment. Prevention & Education explains how to approach that conversation.','prevention','Read the guides']};
const out=document.getElementById('next-out');
document.querySelectorAll('[data-choice]').forEach(b=>b.onclick=()=>{const c=NS[b.dataset.choice];
document.querySelectorAll('[data-choice]').forEach(x=>x.setAttribute('aria-pressed',x===b));
out.innerHTML='';const p=document.createElement('p');p.textContent=c[0];const a=document.createElement('a');a.className='btn btn-primary';a.href='index.php?action=info&page='+c[1];a.textContent=c[2]+' →';out.append(p,a);out.hidden=false});

const q=document.getElementById('quiz');
if(q)q.querySelectorAll('[data-ans]').forEach(b=>b.onclick=()=>{q.querySelector('.fb').textContent=(b.dataset.ans==='myth'?'Correct, it is a myth. ':'Not quite, it is a myth. ')+'Asking for help takes courage. It is a strength, not a weakness.'});

document.querySelectorAll('form[data-proto]').forEach(f=>f.addEventListener('submit',e=>{e.preventDefault();f.querySelector('.proto-result').hidden=false}));

const cf=document.getElementById('capsule-form');
if(cf){cf.date.min=new Date(Date.now()+864e5).toISOString().slice(0,10);
cf.onsubmit=e=>{e.preventDefault();const err=document.getElementById('capsule-error');document.getElementById('capsule-ok').hidden=true;if(!cf.msg.value.trim()||!cf.date.value||cf.date.value<cf.date.min){err.textContent='Enter a message and a future date.';err.hidden=false;return}err.hidden=true;try{const l=JSON.parse(localStorage.getItem('khotwa_capsules')||'[]');l.push({msg:cf.msg.value,date:cf.date.value});localStorage.setItem('khotwa_capsules',JSON.stringify(l))}catch(_){err.textContent="Unable to save in this browser.";err.hidden=false;return}
cf.reset();document.getElementById('capsule-ok').hidden=false}}
})();
