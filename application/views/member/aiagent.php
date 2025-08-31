

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
@media (max-width: 600px) {
  .chatbox-big-card {
    width: 100vw;
    max-width: 100vw;
    height: 100vh;
    max-height: 100vh;
    border-radius: 0;
    box-shadow: 0 4px 24px rgba(23,125,255,0.10);
    margin: 0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .card-body.p-0 {
    display: flex;
    flex-direction: column;
    height: 100vh;
    min-height: 0;
    padding: 0;
    overflow: hidden;
    box-sizing: border-box;
  }
  .chatbox-messages {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    background: #f8fafc;
  }
  .chatbox-footer {
    flex-shrink: 0;
    background: #fff;
  }
}

/* CARD UTAMA CHATBOX */
.chatbox-big-card {
  width: 100%;
  margin: 0 auto;         /* biar center */
  border-radius: 16px;
  box-shadow: 0 4px 24px rgba(23,125,255,0.10);
  display: flex;
  flex-direction: column;
  overflow: hidden;       /* cegah isi keluar */
  box-sizing: border-box;
  background: #fff;
    max-width: 2800px;       /* lebih besar di desktop */
    height: 1100px;
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
  background: #fff;
  width: 98vw;
  max-width: 98vw;
  height: 340px;
  max-height: 340px;
  border-radius: 8px;
  box-shadow: 0 4px 24px rgba(23,125,255,0.10);
  margin: 8px 0 0 6vw;
  display: flex;
  flex-direction: column;
  overflow: hidden;
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