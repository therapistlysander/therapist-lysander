@extends('admin.layouts.admin')
@section('title', 'Media')
@section('page_title', 'Media Library')

@section('page_styles')
<style>
/* Toolbar */
.media-toolbar {
  position: sticky; top: 56px; z-index: 30;
  display: flex; align-items: center; gap: 12px;
  padding: 12px 0; margin-bottom: 20px;
  background: #f1f3f5; border-bottom: 1px solid #e5e7eb;
  margin: -28px -28px 24px; padding: 12px 28px;
}
.media-toolbar__left { display: flex; align-items: center; gap: 10px; }
.media-toolbar__right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
.media-upload-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; background: #5a9e97; color: #fff;
  border: none; border-radius: 6px; font-size: 13px; font-weight: 500;
  cursor: pointer; transition: background 0.15s;
}
.media-upload-btn:hover { background: #4a8e87; }
.media-upload-btn svg { width: 16px; height: 16px; }
.media-bulk-delete-btn {
  display: none; align-items: center; gap: 6px;
  padding: 8px 14px; background: #fef2f2; color: #dc2626;
  border: 1px solid #fecaca; border-radius: 6px; font-size: 12px; font-weight: 500;
  cursor: pointer; transition: background 0.15s;
}
.media-bulk-delete-btn.visible { display: inline-flex; }
.media-bulk-delete-btn:hover { background: #fee2e2; }
.media-view-toggle { display: flex; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
.media-view-toggle button {
  padding: 6px 10px; background: #fff; border: none; cursor: pointer;
  color: #6b7280; transition: background 0.15s, color 0.15s;
  display: flex; align-items: center;
}
.media-view-toggle button.active { background: #5a9e97; color: #fff; }
.media-view-toggle button + button { border-left: 1px solid #e5e7eb; }
.media-view-toggle svg { width: 16px; height: 16px; }
.media-file-count { font-size: 12px; color: #6b7280; }

/* Progress */
.media-progress { display: none; margin-bottom: 16px; padding: 10px 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; }
.media-progress.visible { display: block; }
.media-progress__label { font-size: 12px; color: #374151; margin-bottom: 6px; }
.media-progress__bar { height: 4px; background: #e5e7eb; border-radius: 2px; overflow: hidden; }
.media-progress__fill { height: 100%; background: #5a9e97; width: 0%; transition: width 0.2s; border-radius: 2px; }

/* Dropzone */
.media-dropzone { position: relative; min-height: 300px; }
.media-dropzone__overlay {
  display: none; position: absolute; inset: 0; z-index: 20;
  background: rgba(90, 158, 151, 0.08); border: 2px dashed #5a9e97;
  border-radius: 12px; align-items: center; justify-content: center;
  flex-direction: column; gap: 8px;
}
.media-dropzone--dragover .media-dropzone__overlay { display: flex; }
.media-dropzone__overlay svg { width: 48px; height: 48px; color: #5a9e97; }
.media-dropzone__overlay span { font-size: 14px; font-weight: 500; color: #5a9e97; }

/* Grid View */
.media-grid {
  display: none; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
  gap: 16px;
}
.media-grid.active { display: grid; }
.media-card {
  position: relative; background: #fff; border: 2px solid #e5e7eb;
  border-radius: 10px; overflow: hidden; cursor: pointer;
  transition: border-color 0.15s, box-shadow 0.15s, opacity 0.25s, transform 0.25s;
}
.media-card:hover { border-color: #d1d5db; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.media-card.selected { border-color: #5a9e97; box-shadow: 0 0 0 3px rgba(90,158,151,0.15); }
.media-card.removing { opacity: 0; transform: scale(0.95); }
.media-card__thumb {
  width: 100%; height: 160px; display: flex; align-items: center; justify-content: center;
  background: #f9fafb; overflow: hidden;
}
.media-card__thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.media-card__thumb-icon { font-size: 40px; color: #9ca3af; }
.media-card__check {
  position: absolute; top: 8px; left: 8px; width: 20px; height: 20px;
  accent-color: #5a9e97; cursor: pointer; opacity: 0;
  transition: opacity 0.15s;
}
.media-card:hover .media-card__check, .media-card.selected .media-card__check { opacity: 1; }
.media-card__info { padding: 10px 12px; }
.media-card__name {
  font-size: 11px; color: #374151; margin: 0 0 2px;
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
  font-weight: 500;
}
.media-card__size { font-size: 10px; color: #9ca3af; margin: 0; }

/* List View */
.media-list { display: none; }
.media-list.active { display: block; }
.media-list table { width: 100%; }
.media-list .list-thumb { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; }
.media-list .list-name { font-size: 13px; color: #374151; cursor: pointer; }
.media-list .list-name:hover { color: #5a9e97; }

/* Detail Modal */
.media-modal { display: none; position: fixed; inset: 0; z-index: 1000; align-items: center; justify-content: center; }
.media-modal.visible { display: flex; }
.media-modal__backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.5); }
.media-modal__content {
  position: relative; background: #fff; border-radius: 12px;
  max-width: 720px; width: 90%; max-height: 80vh; overflow: hidden;
  display: grid; grid-template-columns: 1fr 280px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.media-modal__preview {
  background: #f3f4f6; display: flex; align-items: center; justify-content: center;
  padding: 20px; min-height: 300px;
}
.media-modal__preview img { max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 4px; }
.media-modal__panel { padding: 24px; border-left: 1px solid #e5e7eb; display: flex; flex-direction: column; gap: 14px; overflow-y: auto; }
.media-modal__close {
  position: absolute; top: 12px; right: 12px; width: 32px; height: 32px;
  background: #fff; border: 1px solid #e5e7eb; border-radius: 6px;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  font-size: 18px; color: #6b7280; z-index: 10;
}
.media-modal__close:hover { background: #f3f4f6; }
.media-modal__field-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; font-weight: 600; margin: 0; }
.media-modal__field-value { font-size: 13px; color: #1a2332; margin: 2px 0 0; word-break: break-all; }
.media-modal__url-wrap {
  display: flex; align-items: center; gap: 6px;
  background: #f3f4f6; border-radius: 6px; padding: 8px 10px;
}
.media-modal__url-wrap input {
  flex: 1; border: none; background: none; font-size: 11px; color: #374151;
  font-family: ui-monospace, monospace; outline: none;
}
.media-modal__copy-btn {
  padding: 4px 10px; background: #5a9e97; color: #fff; border: none;
  border-radius: 4px; font-size: 11px; cursor: pointer; white-space: nowrap;
}
.media-modal__copy-btn:hover { background: #4a8e87; }

/* Empty state */
.media-empty {
  text-align: center; padding: 60px 20px; color: #9ca3af;
}
.media-empty svg { width: 48px; height: 48px; margin-bottom: 12px; }
.media-empty p { font-size: 14px; margin: 0; }

/* Animation */
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.media-card--new { animation: fadeIn 0.3s ease; }
</style>
@endsection

@section('content')

<!-- Toolbar -->
<div class="media-toolbar">
  <div class="media-toolbar__left">
    <button type="button" class="media-upload-btn" id="upload-trigger">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
      Upload Files
    </button>
    <input type="file" id="file-input" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf" style="display:none;">
    <button type="button" class="media-bulk-delete-btn" id="bulk-delete-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
      Delete Selected (<span id="selected-count">0</span>)
    </button>
  </div>
  <div class="media-toolbar__right">
    <div class="media-view-toggle">
      <button type="button" id="view-grid" class="active" title="Grid view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
      </button>
      <button type="button" id="view-list" title="List view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
      </button>
    </div>
    <span class="media-file-count" id="file-count">{{ $files->count() }} {{ Str::plural('file', $files->count()) }}</span>
  </div>
</div>

<!-- Upload Progress -->
<div class="media-progress" id="upload-progress">
  <div class="media-progress__label" id="progress-label">Uploading...</div>
  <div class="media-progress__bar"><div class="media-progress__fill" id="progress-fill"></div></div>
</div>

<!-- Dropzone -->
<div class="media-dropzone" id="dropzone">
  <div class="media-dropzone__overlay">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
    <span>Drop files here to upload</span>
  </div>

  @if($files->isEmpty())
  <div class="media-empty" id="empty-state">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
    <p>No files uploaded yet. Click "Upload Files" or drag files here.</p>
  </div>
  @endif

  <!-- Grid View -->
  <div class="media-grid active" id="media-grid"></div>

  <!-- List View -->
  <div class="media-list" id="media-list">
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th style="width:50px;"></th><th>Name</th><th>Size</th><th>Modified</th><th style="width:100px;"></th></tr></thead>
        <tbody id="media-list-body"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Detail Modal -->
<div class="media-modal" id="detail-modal">
  <div class="media-modal__backdrop" id="modal-backdrop"></div>
  <div class="media-modal__content">
    <button type="button" class="media-modal__close" id="modal-close">&times;</button>
    <div class="media-modal__preview">
      <img id="modal-preview-img" src="" alt="">
    </div>
    <div class="media-modal__panel">
      <div>
        <p class="media-modal__field-label">Filename</p>
        <p class="media-modal__field-value" id="modal-filename"></p>
      </div>
      <div>
        <p class="media-modal__field-label">Size</p>
        <p class="media-modal__field-value" id="modal-size"></p>
      </div>
      <div>
        <p class="media-modal__field-label">Dimensions</p>
        <p class="media-modal__field-value" id="modal-dims"></p>
      </div>
      <div>
        <p class="media-modal__field-label">Type</p>
        <p class="media-modal__field-value" id="modal-type"></p>
      </div>
      <div>
        <p class="media-modal__field-label">Modified</p>
        <p class="media-modal__field-value" id="modal-date"></p>
      </div>
      <div>
        <p class="media-modal__field-label">URL</p>
        <div class="media-modal__url-wrap">
          <input type="text" id="modal-url" readonly>
          <button type="button" class="media-modal__copy-btn" id="modal-copy">Copy</button>
        </div>
      </div>
      <div style="margin-top:auto;padding-top:12px;border-top:1px solid #e5e7eb;">
        <button type="button" class="btn-admin btn-admin--danger" id="modal-delete" style="width:100%;justify-content:center;">Delete File</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page_scripts')
<script>
(function() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  let files = @json($files);
  let selectedFiles = new Set();
  let currentView = 'grid';

  // DOM refs
  const grid = document.getElementById('media-grid');
  const listBody = document.getElementById('media-list-body');
  const gridWrap = document.getElementById('media-grid');
  const listWrap = document.getElementById('media-list');
  const dropzone = document.getElementById('dropzone');
  const fileInput = document.getElementById('file-input');
  const uploadBtn = document.getElementById('upload-trigger');
  const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
  const selectedCountEl = document.getElementById('selected-count');
  const fileCountEl = document.getElementById('file-count');
  const progressWrap = document.getElementById('upload-progress');
  const progressLabel = document.getElementById('progress-label');
  const progressFill = document.getElementById('progress-fill');
  const emptyState = document.getElementById('empty-state');
  const modal = document.getElementById('detail-modal');

  // ─── Render ───
  function renderGrid() {
    grid.innerHTML = '';
    if (emptyState) emptyState.style.display = files.length === 0 ? '' : 'none';
    files.forEach(file => {
      const ext = file.filename.split('.').pop().toLowerCase();
      const isImage = ['jpg','jpeg','png','gif','webp','svg'].includes(ext);
      const card = document.createElement('div');
      card.className = 'media-card' + (selectedFiles.has(file.filename) ? ' selected' : '');
      card.dataset.filename = file.filename;
      card.innerHTML = `
        <input type="checkbox" class="media-card__check" ${selectedFiles.has(file.filename) ? 'checked' : ''}>
        <div class="media-card__thumb">
          ${isImage ? `<img src="${file.url}" alt="${file.filename}" loading="lazy">` : `<span class="media-card__thumb-icon">&#128196;</span>`}
        </div>
        <div class="media-card__info">
          <p class="media-card__name" title="${file.filename}">${file.filename}</p>
          <p class="media-card__size">${formatSize(file.size)}</p>
        </div>
      `;
      grid.appendChild(card);
    });
  }

  function renderList() {
    listBody.innerHTML = '';
    files.forEach(file => {
      const ext = file.filename.split('.').pop().toLowerCase();
      const isImage = ['jpg','jpeg','png','gif','webp','svg'].includes(ext);
      const tr = document.createElement('tr');
      tr.className = selectedFiles.has(file.filename) ? 'selected' : '';
      tr.dataset.filename = file.filename;
      tr.innerHTML = `
        <td><input type="checkbox" class="row-check" ${selectedFiles.has(file.filename) ? 'checked' : ''}></td>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            ${isImage ? `<img src="${file.url}" class="list-thumb" alt="">` : `<span style="width:40px;height:40px;background:#f3f4f6;border-radius:4px;display:flex;align-items:center;justify-content:center;">&#128196;</span>`}
            <span class="list-name">${file.filename}</span>
          </div>
        </td>
        <td style="font-size:12px;color:#6b7280;">${formatSize(file.size)}</td>
        <td style="font-size:12px;color:#6b7280;">${formatDate(file.modified)}</td>
        <td style="text-align:right;">
          <button type="button" class="btn-admin btn-admin--outline detail-btn" style="padding:4px 10px;font-size:11px;">Details</button>
        </td>
      `;
      listBody.appendChild(tr);
    });
  }

  function render() {
    renderGrid();
    renderList();
    updateCount();
  }

  function updateCount() {
    fileCountEl.textContent = files.length + ' ' + (files.length === 1 ? 'file' : 'files');
    selectedCountEl.textContent = selectedFiles.size;
    if (selectedFiles.size > 0) {
      bulkDeleteBtn.classList.add('visible');
    } else {
      bulkDeleteBtn.classList.remove('visible');
    }
  }

  function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
  }

  function formatDate(ts) {
    const d = new Date(ts * 1000);
    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
  }

  // ─── Selection ───
  function toggleSelect(filename) {
    if (selectedFiles.has(filename)) {
      selectedFiles.delete(filename);
    } else {
      selectedFiles.add(filename);
    }
    render();
  }

  // ─── Event Delegation: Grid ───
  grid.addEventListener('click', function(e) {
    const card = e.target.closest('.media-card');
    if (!card) return;
    const filename = card.dataset.filename;
    if (e.target.classList.contains('media-card__check')) {
      toggleSelect(filename);
      return;
    }
    // Click on thumbnail opens detail
    if (e.target.closest('.media-card__thumb')) {
      showDetail(filename);
      return;
    }
    // Click elsewhere on card toggles selection
    toggleSelect(filename);
  });

  // ─── Event Delegation: List ───
  listBody.addEventListener('click', function(e) {
    const tr = e.target.closest('tr');
    if (!tr) return;
    const filename = tr.dataset.filename;
    if (e.target.classList.contains('row-check')) {
      toggleSelect(filename);
      return;
    }
    if (e.target.classList.contains('list-name') || e.target.classList.contains('detail-btn')) {
      showDetail(filename);
      return;
    }
    toggleSelect(filename);
  });

  // ─── View Toggle ───
  document.getElementById('view-grid').addEventListener('click', function() {
    currentView = 'grid';
    gridWrap.classList.add('active');
    listWrap.classList.remove('active');
    this.classList.add('active');
    document.getElementById('view-list').classList.remove('active');
  });
  document.getElementById('view-list').addEventListener('click', function() {
    currentView = 'list';
    listWrap.classList.add('active');
    gridWrap.classList.remove('active');
    this.classList.add('active');
    document.getElementById('view-grid').classList.remove('active');
  });

  // ─── Upload ───
  uploadBtn.addEventListener('click', () => fileInput.click());
  fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) uploadFiles(fileInput.files);
    fileInput.value = '';
  });

  function uploadFiles(fileList) {
    const queue = Array.from(fileList);
    let idx = 0;

    function next() {
      if (idx >= queue.length) {
        progressWrap.classList.remove('visible');
        progressFill.style.width = '0%';
        return;
      }
      const file = queue[idx];
      const allowed = ['jpg','jpeg','png','gif','webp','svg','pdf'];
      const ext = file.name.split('.').pop().toLowerCase();
      if (!allowed.includes(ext)) {
        showFlash('error', file.name + ': unsupported file type.');
        idx++; next(); return;
      }
      if (file.size > 10 * 1024 * 1024) {
        showFlash('error', file.name + ': exceeds 10 MB limit.');
        idx++; next(); return;
      }

      progressWrap.classList.add('visible');
      progressLabel.textContent = 'Uploading: ' + file.name + ' (' + (idx + 1) + '/' + queue.length + ')';
      progressFill.style.width = '0%';

      const formData = new FormData();
      formData.append('file', file);
      formData.append('_token', csrfToken);

      const xhr = new XMLHttpRequest();
      xhr.open('POST', '{{ route("admin.media.upload") }}');
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          progressFill.style.width = Math.round((e.loaded / e.total) * 100) + '%';
        }
      };
      xhr.onload = function() {
        if (xhr.status === 201) {
          const newFile = JSON.parse(xhr.responseText);
          files.unshift(newFile);
          render();
          // Animate the new card
          const firstCard = grid.querySelector('.media-card');
          if (firstCard) firstCard.classList.add('media-card--new');
        } else {
          let msg = 'Upload failed.';
          try { msg = JSON.parse(xhr.responseText).message || msg; } catch(e) {}
          showFlash('error', msg);
        }
        idx++; next();
      };
      xhr.onerror = function() {
        showFlash('error', 'Network error during upload.');
        idx++; next();
      };
      xhr.send(formData);
    }
    next();
  }

  // ─── Drag & Drop ───
  let dragCounter = 0;
  dropzone.addEventListener('dragenter', function(e) {
    e.preventDefault();
    dragCounter++;
    dropzone.classList.add('media-dropzone--dragover');
  });
  dropzone.addEventListener('dragover', function(e) { e.preventDefault(); });
  dropzone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    dragCounter--;
    if (dragCounter <= 0) {
      dragCounter = 0;
      dropzone.classList.remove('media-dropzone--dragover');
    }
  });
  dropzone.addEventListener('drop', function(e) {
    e.preventDefault();
    dragCounter = 0;
    dropzone.classList.remove('media-dropzone--dragover');
    if (e.dataTransfer.files.length > 0) {
      uploadFiles(e.dataTransfer.files);
    }
  });

  // ─── Delete ───
  function deleteFile(filename) {
    if (!confirm('Delete "' + filename + '"? This cannot be undone.')) return;
    fetch('/admin/media/' + encodeURIComponent(filename), {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    })
    .then(r => { if (!r.ok) throw new Error('Delete failed'); return r.json(); })
    .then(() => {
      // Animate removal
      const card = grid.querySelector('[data-filename="' + CSS.escape(filename) + '"]');
      if (card) card.classList.add('removing');
      setTimeout(() => {
        files = files.filter(f => f.filename !== filename);
        selectedFiles.delete(filename);
        render();
      }, 250);
    })
    .catch(() => showFlash('error', 'Failed to delete file.'));
  }

  // ─── Bulk Delete ───
  bulkDeleteBtn.addEventListener('click', function() {
    const count = selectedFiles.size;
    if (!confirm('Delete ' + count + ' file(s)? This cannot be undone.')) return;
    fetch('/admin/media/bulk-delete', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
      body: JSON.stringify({ filenames: Array.from(selectedFiles) })
    })
    .then(r => { if (!r.ok) throw new Error('Bulk delete failed'); return r.json(); })
    .then(data => {
      // Animate removal
      selectedFiles.forEach(fn => {
        const card = grid.querySelector('[data-filename="' + CSS.escape(fn) + '"]');
        if (card) card.classList.add('removing');
      });
      setTimeout(() => {
        files = files.filter(f => !selectedFiles.has(f.filename));
        selectedFiles.clear();
        render();
        showFlash('success', data.deleted + ' file(s) deleted.');
      }, 250);
    })
    .catch(() => showFlash('error', 'Failed to delete files.'));
  });

  // ─── Detail Modal ───
  function showDetail(filename) {
    const file = files.find(f => f.filename === filename);
    if (!file) return;

    document.getElementById('modal-preview-img').src = file.url;
    document.getElementById('modal-filename').textContent = filename;
    document.getElementById('modal-size').textContent = formatSize(file.size);
    document.getElementById('modal-date').textContent = formatDate(file.modified);
    document.getElementById('modal-url').value = file.url;
    document.getElementById('modal-dims').textContent = 'Loading...';
    document.getElementById('modal-type').textContent = '...';
    modal.classList.add('visible');

    // Fetch extended details
    fetch('/admin/media/' + encodeURIComponent(filename) + '/details', {
      headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
      document.getElementById('modal-dims').textContent = data.dimensions
        ? data.dimensions.width + ' x ' + data.dimensions.height + ' px'
        : 'N/A';
      document.getElementById('modal-type').textContent = data.mime || 'Unknown';
    })
    .catch(() => {
      document.getElementById('modal-dims').textContent = 'N/A';
      document.getElementById('modal-type').textContent = 'Unknown';
    });
  }

  document.getElementById('modal-close').addEventListener('click', closeModal);
  document.getElementById('modal-backdrop').addEventListener('click', closeModal);
  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

  function closeModal() { modal.classList.remove('visible'); }

  document.getElementById('modal-copy').addEventListener('click', function() {
    const url = document.getElementById('modal-url').value;
    navigator.clipboard.writeText(url).then(() => {
      this.textContent = 'Copied!';
      setTimeout(() => { this.textContent = 'Copy'; }, 1500);
    });
  });

  document.getElementById('modal-delete').addEventListener('click', function() {
    const filename = document.getElementById('modal-filename').textContent;
    closeModal();
    deleteFile(filename);
  });

  // ─── Flash Message ───
  function showFlash(type, msg) {
    const div = document.createElement('div');
    div.className = 'admin-alert admin-alert--' + type;
    div.textContent = msg;
    const content = document.querySelector('.admin-content');
    content.insertBefore(div, content.firstChild);
    setTimeout(() => { div.style.transition = 'opacity 0.4s'; div.style.opacity = '0'; setTimeout(() => div.remove(), 400); }, 3000);
  }

  // ─── Init ───
  render();
})();
</script>
@endsection
