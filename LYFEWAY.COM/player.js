document.addEventListener('DOMContentLoaded', function(){
  const audio = document.getElementById('audio');
  const titleEl = document.getElementById('title');
  const artistEl = document.getElementById('artist');
  const cover = document.getElementById('cover');
  const tracks = document.querySelectorAll('#tracks li');

  function setTrack(li){
    const src = li.dataset.src;
    const t = li.dataset.title || li.textContent.trim();
    if(!src) return;
    // ensure properly encoded src is used
    audio.src = src;
    titleEl.textContent = t;
    artistEl.textContent = 'Life Way Charismatic Church';
    cover.textContent = t.split(' ').slice(0,2).map(s=>s[0]||'').join('').toUpperCase();
    audio.play().catch(()=>{ /* autoplay may be blocked */ });
    // highlight current
    document.querySelectorAll('#tracks li').forEach(n=>n.classList.remove('playing'));
    li.classList.add('playing');
  }

  tracks.forEach(li=>{
    li.addEventListener('click', ()=> setTrack(li));
  });

  // If audio ends, move to next track
  audio.addEventListener('ended', ()=>{
    const list = Array.from(document.querySelectorAll('#tracks li'));
    const current = list.findIndex(li => li.dataset.src === audio.src);
    const next = (current + 1) % list.length;
    if(list[next]) setTrack(list[next]);
  });
});