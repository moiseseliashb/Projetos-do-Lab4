document.addEventListener('DOMContentLoaded', ()=>{
  // tema
  const root = document.documentElement;
  const stored = localStorage.getItem('theme') || 'light';
  root.setAttribute('data-theme', stored);
  const themeToggle = document.getElementById('theme-toggle');
  if(themeToggle){
    themeToggle.addEventListener('click', ()=>{
      const cur = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', cur);
      localStorage.setItem('theme', cur);
    })
  }

  const form = document.getElementById('tx-form');
  const list = document.getElementById('tx-list');
  const filterType = document.getElementById('filter-type');
  const filterCategory = document.getElementById('filter-category');
  let transactions = [];

  function renderTransactions(){
    const typeValue = filterType?.value || 'all';
    const categoryValue = filterCategory?.value || 'all';
    const filtered = transactions.filter(tx => {
      const matchesType = typeValue === 'all' || tx.type === typeValue;
      const matchesCategory = categoryValue === 'all' || String(tx.category_id) === categoryValue;
      return matchesType && matchesCategory;
    });
    list.innerHTML = '';
    if(filtered.length === 0){
      list.innerHTML = '<li class="empty">Nenhum lançamento encontrado.</li>';
      return;
    }
    filtered.forEach(tx=>{
      const li = document.createElement('li');
      li.className = `entry ${tx.type}`;
      li.innerHTML = `
        <div class="entry-main"><strong>${tx.description}</strong><span class="muted">${tx.date} · ${tx.category}</span></div>
        <div class="entry-value">${tx.type === 'income' ? '+' : '-'} R$ ${Number(tx.amount).toFixed(2)}</div>
      `;
      list.appendChild(li);
    });
  }

  const balanceEl = document.getElementById('balance');
  const incomeEl = document.getElementById('income');
  const expenseEl = document.getElementById('expense');

  function refreshSummary(){
    const visible = transactions.filter(tx => {
      const typeValue = filterType?.value || 'all';
      const categoryValue = filterCategory?.value || 'all';
      const matchesType = typeValue === 'all' || tx.type === typeValue;
      const matchesCategory = categoryValue === 'all' || String(tx.category_id) === categoryValue;
      return matchesType && matchesCategory;
    });
    const totalIncome = visible.filter(tx => tx.type === 'income').reduce((sum, tx) => sum + Number(tx.amount), 0);
    const totalExpense = visible.filter(tx => tx.type === 'expense').reduce((sum, tx) => sum + Number(tx.amount), 0);
    const balance = totalIncome - totalExpense;
    if(balanceEl) balanceEl.textContent = `R$ ${balance.toFixed(2)}`;
    if(incomeEl) incomeEl.textContent = `R$ ${totalIncome.toFixed(2)}`;
    if(expenseEl) expenseEl.textContent = `R$ ${totalExpense.toFixed(2)}`;
  }

  async function fetchTransactions(){
    try{
      const res = await fetch('api/transactions.php', { credentials: 'same-origin' });
      const data = await res.json();
      transactions = Array.isArray(data) ? data : [];
      refreshSummary();
      renderTransactions();
    }catch(e){ console.error(e) }
  }

  form.addEventListener('submit', async (ev)=>{
    ev.preventDefault();
    const payload = {
      date: document.getElementById('date').value,
      description: document.getElementById('desc').value,
      amount: parseFloat(document.getElementById('amount').value),
      type: document.getElementById('type').value,
      category_id: parseInt(document.getElementById('category').value, 10)
    };
    try{
      await fetch('api/transactions.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        credentials: 'same-origin',
        body: JSON.stringify(payload)
      });
      form.reset();
      fetchTransactions();
    }catch(e){ console.error(e) }
  });

  filterType?.addEventListener('change', renderTransactions);
  filterCategory?.addEventListener('change', renderTransactions);

  fetchTransactions();
});
