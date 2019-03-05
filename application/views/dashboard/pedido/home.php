<style type="text/css">
  .card {
    color: #000;
    box-shadow: 3px 18px 5px 0px rgba(222,222,222,1);
  }
  .card-header{
    padding: 8px;
    text-align: center;
  }
  .card-body{
    font-size: 0.92em;
    padding: 0px 16px 16px;
    font-weight: bold;
      height: 200px;
  }
  .card-body .ads{
    margin-top: 8px
  }
  .card-azul {
    background-color: #88E3F8;
    transform: rotate(-2deg);
  }
  .card-palha {
    transform: rotate(-1deg);
    background-color: #FEE882;
  }
  .card-verde {
    transform: rotate(2deg);
    background-color: #99ECBE;
  }
  .card-azul .card-header {
    background-color: #6DCCE5;
  }
  .card-palha .card-header {
    background-color: #EBD367;
  }
  .card-verde .card-header {
    background-color: #7FD6A4;
  }

</style>
<div class="w3-main" style="margin-top:70px;">
  <div class="w3-panel">
    <div class="w3-container w3-white w3-padding-16 w3-center" style="min-height: 85vh;">
      <div class="w3-row-padding" id="basePedido">
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/pedido/main.js');?>"></script>