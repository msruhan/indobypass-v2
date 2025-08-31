
<div class="page-header">
	<div class="d-flex justify-content-between">
		<h3 class="fw-bold">AI Agent INDOBYPASS</h3>
		<ul class="breadcrumbs">
			<li class="nav-home">
				<a href="<?= site_url() ?>member/dashboard">
					<i class="icon-home"></i>
				</a>
			</li>
			<li class="separator">
				<i class="icon-arrow-right"></i>
			</li>
			<li class="nav-item">
				<a href="#">AI Agent</a>
			</li>
		</ul>
	</div>
</div>
<div class="chatbox-big-container">
  <div class="chatbox-big-card">
	<div class="card-body p-0" style="background:#fff;height:100%;display:flex;flex-direction:column;">
	  <?php $this->load->view('chatbox_view'); ?>
	</div>
  </div>
</div>


<!-- AI Agent Chatbox Lebih Besar -->
<style>
/* CARD UTAMA CHATBOX */
.chatbox-big-card {
  width: 100%;
  max-width: 420px;       /* batas maksimal */
  height: 70%;
  margin: 0 auto;         /* biar center */
  border-radius: 16px;
  box-shadow: 0 4px 24px rgba(23,125,255,0.10);
  display: flex;
  flex-direction: column;
  overflow: hidden;       /* cegah isi keluar */
  box-sizing: border-box;
  background: #fff;
}

/* HEADER CHATBOX */
.chatbox-header {
  background: #1877f2;
  color: #fff;
  padding: 10px 15px;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}

/* AREA PESAN */
.chatbox-messages {
  flex: 1;
  padding: 12px;
  overflow-y: auto;
  background: #f8fafc;
}

/* BALON CHAT */
.chat-bubble {
  max-width: 80%;              /* jangan lebih dari 80% lebar card */
  padding: 10px 14px;
  margin: 6px 0;
  border-radius: 12px;
  font-size: 14px;
  line-height: 1.4;
  word-wrap: break-word;       /* pecah kata panjang */
  overflow-wrap: break-word;
  box-sizing: border-box;
}

/* PESAN DARI BOT */
.chat-bubble.bot {
  background: #fff;
  border: 1px solid #e5e7eb;
  align-self: flex-start;
  color: #111827;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

/* PESAN DARI USER */
.chat-bubble.user {
  background: #1877f2;
  color: #fff;
  align-self: flex-end;
  border-radius: 12px 12px 0 12px;
}

/* FOOTER (INPUT) */
.chatbox-footer {
  padding: 10px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  gap: 8px;
}

.chatbox-input {
  flex: 1;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 8px 12px;
  font-size: 14px;
  outline: none;
}

.chatbox-send-btn {
  background: #1877f2;
  border: none;
  border-radius: 12px;
  color: #fff;
  padding: 8px 16px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
}

.chatbox-send-btn:hover {
  background: #0f5ec0;
}


</style>