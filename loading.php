<style>
  .loader-circle-11 {
    position: relative;
    width: 100px;
    height: 100px;
    transform-style: preserve-3d;
    perspective: 400px;
  }
  .text__loading{
    font-size: 20px;
    font-weight: 100;
  }
  .loader-circle-11 .arc {
    position: absolute;
    content: "";
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border-bottom: 7px solid #fff;
  }
  .loader-circle-11 .arc:nth-child(1) {
    animation: rotate1 1.15s linear infinite;
  }
  .loader-circle-11 .arc:nth-child(2) {
    animation: rotate2 1.15s linear infinite;
  }
  .loader-circle-11 .arc:nth-child(3) {
    animation: rotate3 1.15s linear infinite;
  }
  .loading .arc:nth-child(1) {
    animation-delay: -0.8s;
  }
  .loader-circle-11 .arc:nth-child(2) {
    animation-delay: -0.4s;
  }
  .loader-circle-11 .arc:nth-child(3) {
    animation-delay: 0s;
  }
  @keyframes rotate1 {
    from {
      transform: rotateX(35deg) rotateY(-45deg) rotateZ(0);
    }
    to {
      transform: rotateX(35deg) rotateY(-45deg) rotateZ(1turn);
    }
  }
  @keyframes rotate2 {
    from {
      transform: rotateX(50deg) rotateY(10deg) rotateZ(0);
    }
    to {
      transform: rotateX(50deg) rotateY(10deg) rotateZ(1turn);
    }
  }
  @keyframes rotate3 {
    from {
      transform: rotateX(35deg) rotateY(55deg) rotateZ(0);
    }
    to {
      transform: rotateX(35deg) rotateY(55deg) rotateZ(1turn);
    }
  }

  .bg_color{
    background:none;
    outline:none;
    border:none;
    color: #fff;
}
.proccess{
  letter-spacing: 1.2px;
}
#show_loading{
  background: rgba( 0, 0, 0, 0.35 );
box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
backdrop-filter: blur( 0px );
-webkit-backdrop-filter: blur( 0px );
}


  </style>

<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!-- Modal -->
<div class="modal fade" id="show_loading" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content bg_color">
            <center>
                <div class="loader-circle-11">
                    <div class="arc"></div>
                    <div class="arc"></div>
                    <div class="arc"></div>
                </div>
                <p class="proccess"><b>ກະລຸນາລໍຖ້າ......</b></p>
            </center>
        </div>
    </div>
</div>