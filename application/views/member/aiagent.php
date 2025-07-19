

<!-- AI Agent Chatbox Lebih Besar -->
<style>
  .chatbox-big-container {
	width: 100vw;
	height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	background: #f4f7fa;
	margin: 0;
	padding: 0;
  }
  .chatbox-big-card {
	width: 1400px;
	max-width: 99vw;
	height: 1100px;
	max-height: 98vh;
	box-shadow: 0 20px 80px rgba(23,125,255,0.25);
	border-radius: 44px;
	overflow: hidden;
	background: #fff;
	display: flex;
	flex-direction: column;
	margin: 0;
  }
  /* Responsive */
  @media (max-width: 1700px) {
	.chatbox-big-card { width: 99vw; height: 96vh; border-radius: 0; }
  }
  /* Responsive khusus mobile */
  @media (max-width: 600px) {
  .chatbox-big-container {
	align-items: flex-start;
	justify-content: flex-start;
	padding-top: 0;
	padding-bottom: 0;
	background: #f4f7fa;
	min-height: 100dvh;
	width: 100vw;
	overflow: hidden;
  }
  .chatbox-big-card {
	width: 88vw;
	max-width: 88vw;
	height: 80dvh;
	max-height: 80dvh;
	border-radius: 12px;
	box-shadow: 0 4px 24px rgba(23,125,255,0.10);
	margin: 12px auto 0 auto;
	display: flex;
	flex-direction: column;
	overflow: hidden;
  }
	.card-body.p-0 {
	  height: 100%;
	  min-height: 0;
	  padding: 0;
	  overflow-y: auto;
	  overflow-x: hidden;
	  box-sizing: border-box;
	}
  }
</style>
<div class="chatbox-big-container">
  <div class="chatbox-big-card">
	<div class="card-body p-0" style="background:#fff;height:100%;display:flex;flex-direction:column;">
	  <?php $this->load->view('chatbox_view'); ?>
	</div>
  </div>
</div>