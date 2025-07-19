

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
	width: 1100px;
	max-width: 99vw;
	height: 900px;
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
</style>
<div class="chatbox-big-container">
  <div class="chatbox-big-card">
	<div class="card-body p-0" style="background:#fff;height:100%;display:flex;flex-direction:column;">
	  <?php $this->load->view('chatbox_view'); ?>
	</div>
  </div>
</div>