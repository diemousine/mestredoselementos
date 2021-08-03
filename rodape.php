      <div class="row">
        <div class="col-sm-12">
          <hr />
          <div class="col-sm-4">
            &nbsp;
          </div>
          <div class="col-sm-4 text-center">
            <span class="glyphicon glyphicon-copyright-mark"></span> 2015 <a href="https://www.facebook.com/diemousine" target="_blank" title="Perfil do autor">Diego Socrates Dias Mousine</a>
          </div>
          <div class="col-sm-4 text-right">
            <a><span class='glyphicon glyphicon-info-sign'></span> Sobre</a> &#183;
            <a><span class='glyphicon glyphicon-question-sign'></span> Ajuda</a> &#183;
            <a><span class='glyphicon glyphicon-envelope'></span> Contato</a>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/credencial.js"></script>
    <script src="js/js.js"></script>
    <script src="js/baralho.js"></script>
    <script type="text/javascript">
    function loadScript(url, callback){
      // Adding the script tag to the head as suggested before
      var head = document.getElementsByTagName('head')[0];
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = url;

      // Then bind the event to the callback function.
      // There are several events for cross browser compatibility.
      script.onreadystatechange = callback;
      script.onload = callback;

      // Fire the loading
      head.appendChild(script);
    }
    </script>
  </body>
</html>