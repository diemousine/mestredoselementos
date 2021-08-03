// Este arquivo foi criado para uso único e particular no site mestredoselementos

$("#menu-links").on("click", function() { $(".active").removeAttr("class"); $(this).attr("class", "active"); $("#conteudo-principal").load("links.php"); });
$("#menu-ajuda").on("click", function() { $(".active").removeAttr("class"); $(this).attr("class", "active"); $("#conteudo-principal").load("ajuda.php");});