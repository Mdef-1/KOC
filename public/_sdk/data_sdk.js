(function(){
  const STORAGE_KEY = 'koc_inventory';
  let handler = null;

  function load(){
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch(e){ return []; }
  }
  function save(data){
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(data)); } catch(e){}
  }
  function notify(){
    if (handler && typeof handler.onDataChanged === 'function'){
      try { handler.onDataChanged(load()); } catch(e) { /* noop */ }
    }
  }
  function genId(){ return Date.now().toString(36) + Math.random().toString(36).slice(2); }

  window.dataSdk = {
    async init(h){
      handler = h || null;
      notify();
      return { isOk: true };
    },
    async create(record){
      if (!record || !record.product_id) return { isOk: false, message: 'product_id required' };
      const data = load();
      const idx = data.findIndex(r => r.product_id === record.product_id);
      if (idx !== -1) {
        // Upsert behavior just in case
        data[idx] = Object.assign({}, data[idx], record);
      } else {
        data.push(Object.assign({ id: genId() }, record));
      }
      save(data); notify();
      return { isOk: true };
    },
    async update(record){
      if (!record || (!record.product_id && !record.id)) return { isOk: false, message: 'identifier required' };
      const data = load();
      const idx = data.findIndex(r => (record.product_id && r.product_id === record.product_id) || (record.id && r.id === record.id));
      if (idx === -1) return { isOk: false, message: 'not found' };
      data[idx] = Object.assign({}, data[idx], record);
      save(data); notify();
      return { isOk: true };
    },
    getAll(){ return load(); }
  };

  window.addEventListener('storage', (e) => { if (e.key === STORAGE_KEY) notify(); });
})();
