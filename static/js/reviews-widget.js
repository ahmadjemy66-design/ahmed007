(function(){
  // Reviews widget - include and call initReviewsWidget({type:'product', id:123, target:'#reviews'})
  function escapeHtml(s){ return String(s||'').replace(/[&<>"]/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }

  async function fetchReviews(type,id,limit=20){
    try{ const res = await fetch(`/api/reviews.php?action=get_reviews&reviewable_type=${encodeURIComponent(type)}&reviewable_id=${encodeURIComponent(id)}&limit=${limit}`);
      return await res.json();
    }catch(e){ console.error(e); return {success:false}; }
  }

  async function render(target, type, id){
    const container = document.querySelector(target);
    if(!container) return;
    container.innerHTML = '<div>جارٍ تحميل المراجعات...</div>';
    const data = await fetchReviews(type,id);
    if(!data.success){ container.innerHTML = '<div>خطأ في تحميل المراجعات</div>'; return; }
    const reviews = data.data || [];
    const html = [];
    html.push('<div class="review-list">');
    if(reviews.length===0) html.push('<div class="empty-state"><p>لا توجد مراجعات بعد</p></div>');
    reviews.forEach(r=>{
      html.push(`<div class="review-item"><div class="meta"><strong>${escapeHtml(r.full_name||'مستخدم')}</strong> <span class="stars">${'★'.repeat(r.rating)}${'☆'.repeat(5-r.rating)}</span> <small>${escapeHtml(r.created_at)}</small></div><h4>${escapeHtml(r.title_ar)}</h4><p>${escapeHtml(r.content_ar)}</p>`);
      if(r.images && r.images.length) html.push('<div class="review-images">'+r.images.map(i=>`<img src="${escapeHtml(i.image_url||i)}"/>`).join('')+'</div>');
      html.push('</div>');
    });
    html.push('</div>');

    // review form
    html.push(`
      <div class="review-form">
        <h3>أضف مراجعة</h3>
        <div class="rating" data-rating="5">${[5,4,3,2,1].map(n=>`<button class="star" data-value="${n}">☆</button>`).join('')}</div>
        <input id="rw_title" placeholder="عنوان المراجعة" />
        <textarea id="rw_content" placeholder="اكتب تجربتك..."></textarea>
        <input id="rw_images" type="file" accept="image/*" multiple />
        <button id="rw_submit">إرسال المراجعة</button>
        <div id="rw_msg"></div>
      </div>
    `);

    container.innerHTML = html.join('');

    // rating interactions
    const ratingEl = container.querySelector('.rating');
    let selected = 5;
    ratingEl.addEventListener('click', (e)=>{
      const b = e.target.closest('button.star'); if(!b) return; selected = parseInt(b.dataset.value); updateStars();
    });
    function updateStars(){ ratingEl.querySelectorAll('button.star').forEach(b=>{ b.textContent = (parseInt(b.dataset.value) <= selected) ? '★' : '☆' }); }
    updateStars();

    container.querySelector('#rw_submit').addEventListener('click', async ()=>{
      const title = container.querySelector('#rw_title').value;
      const content = container.querySelector('#rw_content').value;
      const files = container.querySelector('#rw_images').files;
      const fd = new FormData();
      fd.append('action','create_review');
      fd.append('reviewable_type', type);
      fd.append('reviewable_id', id);
      fd.append('rating', selected);
      fd.append('title_ar', title);
      fd.append('content_ar', content);
      for(let i=0;i<files.length;i++) fd.append('images[]', files[i]);
      container.querySelector('#rw_msg').textContent = 'جاري الإرسال...';
      try{
        const res = await fetch('/api/reviews.php', { method: 'POST', body: fd });
        const data = await res.json();
        if(data.success){ container.querySelector('#rw_msg').textContent = 'تم إرسال المراجعة (قيد المراجعة)'; setTimeout(()=>render(target,type,id),1200); }
        else container.querySelector('#rw_msg').textContent = data.error || data.message || 'خطأ';
      }catch(e){ container.querySelector('#rw_msg').textContent = 'خطأ في الإرسال'; }
    });
  }

  window.initReviewsWidget = render;
})();
