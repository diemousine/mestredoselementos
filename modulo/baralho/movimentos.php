<?php
if(isset($_GET)) {

    if(isset($_COOKIE['id'])) {
        session_id(htmlspecialchars($_COOKIE['id']));
        session_start();

        if(isset($_GET['duelo'])) {

            include_once '../../bdcon.php';
            $conn = bdcon();
            $idduelo = mysqli_real_escape_string($conn, htmlspecialchars($_GET['duelo']));
            
            $consulta = mysqli_query($conn, "SELECT * FROM duelo WHERE idduelo = '$idduelo'");
            $resultado = mysqli_fetch_assoc($consulta);

            // - ROTINA PRINCIPAL
            if(isset($_GET['ordem'])) {
                switch (htmlspecialchars($_GET['ordem'])) {
                    case 'status':
                        // ATUALIZAÇÃO DOS DADOS DO DUELO

                        // - FINALIZA A PARTIDA, CASO O TOTAL DE MÃOS SEJA ATINGIDO OU O TEMPO TOTAL DE JOGO SEJA ATINGIDO
                        if($resultado['maoatual'] > $resultado['ttlmao'] || time() >= $resultado['fim']) {
                            $agora = time();
                            // - IDENTIFICA O VENCEDOR COMO DESAFIANTE
                            if($resultado['ptdesaf'] > $resultado['ptopon']) {
                                mysqli_query($conn, "UPDATE duelo SET idvencedor = iddesaf, idperdedor = idopon, fim = $agora, status = NULL, propriedade = NULL WHERE idduelo = '$idduelo'");
                                // - SE O DESAFIANTE FOR O USUÁRIO, AUMENTA A EXPERIÊNCIA DELE
                                if($_SESSION['idus'] == $resultado['iddesaf']) {
                                    include_once '../credencial/ext_credencial.php';
                                    atualizaXP();
                                }
                                // - RETORNA PARA O COMBATE.JS RESULTADO DA DISPUTA
                                echo(json_encode(($_SESSION['idus'] == $resultado['iddesaf']) ? 1 : 2));

                            // - IDENTIFICA O VENCEDOR COMO O OPONENTE
                            } else if ($resultado['ptdesaf'] < $resultado['ptopon']) {
                                mysqli_query($conn, "UPDATE duelo SET idvencedor = idopon, idperdedor = iddesaf, fim = $agora, status = NULL, propriedade = NULL WHERE idduelo = '$idduelo'");
                                // - SE O DESAFIANTE FOR O USUÁRIO, AUMENTA A EXPERIÊNCIA DELE
                                if($_SESSION['idus'] == $resultado['idopon']) {
                                    include_once '../credencial/ext_credencial.php';
                                    atualizaXP();
                                }
                                // - RETORNA PARA O COMBATE.JS RESULTADO DA DISPUTA
                                echo(json_encode(($_SESSION['idus'] == $resultado['idopon']) ? 1 : 2));

                            // - CASO A PARTIDA TERMINE IGUALADA, NINGUÉM É SETADO COMO VENCEDOR OU PERDEDOR.
                            } else {
                                mysqli_query($conn, "UPDATE duelo SET idvencedor = NULL, idperdedor = NULL, fim = $agora, status = NULL, propriedade = NULL WHERE idduelo = '$idduelo'");
                                // - RETORN PARA O COMBATE.JS RESULTADO DA DISPUTA
                                echo(json_encode(3));
                            }
                        } else {

                            // rotina da Inteligência Artificial
                            if($resultado['idopon'] == 1 && $resultado['status'] == 1) {
                                include_once 'ia.php';
                                if($resultado['propriedade'] == NULL) {
                                    iaSelProp($idduelo);
                                }
                                if($resultado['cartaopon'] == 0) {
                                    iaSelCard($resultado['deckopon'], $idduelo);
                                }
                            }

                            // muda o status da partida, caso a CONTAGEM chegue a 0
                            $agora = time();
                            if($resultado['mudastatus'] < $agora) {
                                $mudastatus = time()+30;
                                $query = ($resultado['status'] == $resultado['iddesaf']) ? 'status = idopon, ptopon = (ptopon +1)' : 'status = iddesaf, ptdesaf = (ptdesaf +1)';
                                mysqli_query($conn, "UPDATE duelo SET $query, cartadesaf = 0, cartaopon = 0, propriedade = NULL, mudastatus = $mudastatus, maoatual = (maoatual +1) WHERE idduelo = '$idduelo'");
                            }

                            // muda o status da partida, caso o jogador já efetuou todas as jogadas
                            if($resultado['status'] == $resultado['iddesaf'] && $resultado['cartadesaf'] != 0 && $resultado['propriedade'] != NULL && $resultado['cartaopon'] == 0) {
                                $mudastatus = time()+30;
                                mysqli_query($conn, "UPDATE duelo SET status = idopon, mudastatus = $mudastatus WHERE idduelo = '$idduelo'");
                            } else if($resultado['status'] == $resultado['idopon'] && $resultado['cartaopon'] != 0 && $resultado['propriedade'] != NULL && $resultado['cartadesaf'] == 0) {
                                $mudastatus = time()+30;
                                mysqli_query($conn, "UPDATE duelo SET status = iddesaf, mudastatus = $mudastatus WHERE idduelo = '$idduelo'");
                            }

                            // Caso os dois jogadores estejam prontos faz comparação das cartas
                            if($resultado['cartadesaf'] != 0 && $resultado['cartaopon'] != 0 && $resultado['propriedade'] != NULL) {
                                include_once 'propriedades.php';
                                if(compare($idduelo) == 1) $query = 'ptdesaf = (ptdesaf +1),';
                                else if(compare($idduelo) == 2) $query = 'ptopon = (ptopon +1),';
                                $mudastatus = time()+30;
                                mysqli_query($conn, "UPDATE duelo SET $query cartadesaf = 0, cartaopon = 0, propriedade = NULL, mudastatus = $mudastatus, maoatual = (maoatual +1) WHERE idduelo = '$idduelo'");
                            }
        
                            // - ATUALIZA AS POSIÇÕES DO DECK
                            $deck = explode(',', ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['deckdes'] : $resultado['deckopon']);
                            // - ATUALIZA A CARTA DO OPONENTE
                            $idcartas = $resultado['cartaopon'];
                            $consCartaOpon = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $idcartas");
                            if(mysqli_affected_rows($conn) > 0) {
                                $resCartaOpon = mysqli_fetch_assoc($consCartaOpon);
                                $cartaOpon = $resCartaOpon['simbolo'];
                            } else {
                                $cartaOpon = "";
                            }
                            // - CARTA DA VEZ
                            $idcartas = ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['cartadesaf'] : $resultado['cartaopon'];
                            $consCartaDaVez = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $idcartas");
                            if(mysqli_affected_rows($conn) > 0) {
                                $resCartaDaVez = mysqli_fetch_assoc($consCartaDaVez);
                                $cartaDaVez = "<div class='col-sm-8'><p><strong>".$resCartaDaVez['num_atom']."</strong></p><h1><strong>".$resCartaDaVez['simbolo']."</strong></h1><p>".$resCartaDaVez['nome']."<br />".$resCartaDaVez['mas_atom']."</p></div><div class='col-sm-4'>";
                                $eletrons = explode(',', $resCartaDaVez['eletrons']);
                                foreach ($eletrons as $e) {
                                    $cartaDaVez .= "<li style='list-style-type: none'>".$e."</li>";
                                }
                                $cartaDaVez .= "</div>";
                            } else {
                                $cartaDaVez = "";
                            }
                            
                            echo(
                                json_encode(
                                    array(
                                        "bandeira" => ($resultado['status'] == $_SESSION['idus']) ? 1 : 2, // - ATUALIZA QUEM ESTÁ JOGANDO
                                        "ptus" => ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['ptdesaf'] : $resultado['ptopon'], // - PONTUAÇÃO DO USUÁRIO
                                        "ptopon" => ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['ptopon'] : $resultado['ptdesaf'], // - PONTUAÇÃO DO OPONENTE
                                        "jogadas" => $resultado['maoatual']."/".$resultado['ttlmao'], // - JOGADAS
                                        "qtddeck" => $deck[0], // - ATUALIZA AS POSIÇÕES DO DECK
                                        "cartaOpon" => $cartaOpon, // - ATUALIZA A CARTA DO OPONENTE
                                        "cartaDaVez" => $cartaDaVez, // - CARTA DA VEZ
                                        "propDaVez" => $resultado['propriedade'], // - PROPRIEDADE DA VEZ
                                    )
                                )
                            );
                        }
                        break;
                        
                    // - ATUALIZAÇÃO DO TIMER
                    case 'timer':
                        echo($tempo = $resultado['fim'] - time());
                        break;

                    case 'deck':
                        $deck = explode(',', ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['deckdes'] : $resultado['deckopon']);
                        if($deck[0] > 0) {
                            $idcartas = $deck[1];
                            $consulta = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $idcartas");
                            if(mysqli_affected_rows($conn) > 0) {
                                $resultado = mysqli_fetch_assoc($consulta);
                                    echo ("
                                        <div class='col-sm-8'>
                                            <p><strong>".$resultado['num_atom']."</strong></p>
                                            <h1><strong>".$resultado['simbolo']."</strong></h1>
                                            <p>".$resultado['nome']."<br />".$resultado['mas_atom']."</p>
                                        </div>
                                        <div class='col-sm-4'>
                                    ");
                                $eletrons = explode(',', $resultado['eletrons']);
                                foreach ($eletrons as $e) {
                                    echo ("<li style='list-style-type: none'>".$e."</li>");
                                }
                                echo ("</div>");
                            }
                        } else {
                            echo ("
                            <div class='col-sm-8'>
                                <p><strong>?</strong></p>
                                <h1><strong>?</strong></h1>
                                <p>?????????<br />(???)</p>
                            </div>
                            <div class='col-sm-4'>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                            </div>
                            ");
                        }
                        break;

                    // ATUALIZA AS POSIÇÕES DA MÃO
                    case 'mao':
                        $mao = explode(',', ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['maodes'] : $resultado['maoopon']);
                        if($mao[0] < 5) {
                            $deck = explode(',', ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['deckdes'] : $resultado['deckopon']);
                            $n = htmlspecialchars($_GET['n']);
                            if($deck[0] > 0) {
                                $idcartas = $deck[1];
                                $consulta = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $idcartas");
                                if(mysqli_affected_rows($conn) > 0) {
                                    $resultado = mysqli_fetch_assoc($consulta);
                                        echo ("
                                        <div class='col-sm-12 text-center'>
                                            <button id='mao-".$n."' value='".$resultado['num_atom']."' class='col-sm-10 btn btn-info' style='height:170px' onClick='selecione(this.id, this.value)'>
                                                <div class='col-sm-8'>
                                                    <p><strong>".$resultado['num_atom']."</strong></p>
                                                    <h1><strong>".$resultado['simbolo']."</strong></h1>
                                                    <p>".$resultado['nome']."<br />".$resultado['mas_atom']."</p>
                                                </div>
                                                <div class='col-sm-4'>
                                                ");
                                        $eletrons = explode(',', $resultado['eletrons']);
                                        foreach ($eletrons as $e) {
                                            echo ("<li style='list-style-type: none'>".$e."</li>");
                                    }
                                    echo ("</div>
                                        </button>
                                        </div>");
                                    
                                    $mao[0] += 1;
                                    $carta = str_pad($deck[1], 3, '0', STR_PAD_LEFT);
                                    $mao[$mao[0]] = $carta;
                                    $mao = implode(',', $mao);
                                    
                                    $deck[0] -= 1;
                                    unset($deck[1]);
                                    $deck = implode(',', $deck);

                                    $consulta = mysqli_query($conn, "SELECT * FROM duelo WHERE idduelo = '$idduelo'");
                                    $resultado = mysqli_fetch_assoc($consulta);
                                    
                                    if($_SESSION['idus'] == $resultado['iddesaf']) {
                                        mysqli_query($conn, "UPDATE duelo SET deckdes = '$deck', maodes = '$mao' WHERE idduelo = '$idduelo'");
                                    } else {
                                        mysqli_query($conn, "UPDATE duelo SET deckopon = '$deck', maoopon = '$mao' WHERE idduelo = '$idduelo'");
                                    }
                                }
                            } else {
                                echo ("
                                <div class='col-sm-12 text-center'>
                                    <button id='mao-".$n."' class='col-sm-10 btn btn-info' style='height:170px' disabled>
                                        <div class='col-sm-8'>
                                            <p><strong>?</strong></p>
                                            <h1><strong>?</strong></h1>
                                            <p>?????????<br />(???)</p>
                                        </div>
                                        <div class='col-sm-4'>
                                            <li style='list-style-type: none'>?</li>
                                            <li style='list-style-type: none'>?</li>
                                            <li style='list-style-type: none'>?</li>
                                            <li style='list-style-type: none'>?</li>
                                            <li style='list-style-type: none'>?</li>
                                            <li style='list-style-type: none'>?</li>
                                            <li style='list-style-type: none'>?</li>
                                            <li style='list-style-type: none'>?</li>
                                        </div>
                                    </button>
                                </div>
                                ");
                            }
                        }
                        break;
                        
                    // ATUALIZA AS CARTAS DA MESA
                    case 'mesa':
                        $mesa = explode(',', ($_SESSION['idus'] == $resultado['iddesaf']) ? $resultado['maodes'] : $resultado['maoopon']);
                        $n = htmlspecialchars($_GET['n']);
                        if(isset($mesa[$n])) {
                            $idcartas = $mesa[$n];
                            $consulta = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $idcartas");
                            if(mysqli_affected_rows($conn) > 0) {
                                $resultado = mysqli_fetch_assoc($consulta);
                                echo ("
                                <div class='col-sm-12 text-center'>
                                    <button id='mao-".$n."' value='".$resultado['num_atom']."' class='col-sm-10 btn btn-info' style='height:170px' onClick='selecione(this.id, this.value)'>
                                        <div class='col-sm-8'>
                                            <p><strong>".$resultado['num_atom']."</strong></p>
                                            <h1><strong>".$resultado['simbolo']."</strong></h1>
                                                <p>".$resultado['nome']."<br />".$resultado['mas_atom']."</p>
                                            </div>
                                            <div class='col-sm-4'>
                                            ");
                                    $eletrons = explode(',', $resultado['eletrons']);
                                    foreach ($eletrons as $e) {
                                        echo ("<li style='list-style-type: none'>".$e."</li>");
                                }
                                echo ("</div>
                                    </button>
                                </div>");
                            }
                        } else {
                            echo ("
                            <div class='col-sm-12 text-center'>
                                <button id='mao-".$n."' class='col-sm-10 btn btn-info' style='height:170px' disabled>
                                    <div class='col-sm-8'>
                                        <p><strong>?</strong></p>
                                        <h1><strong>?</strong></h1>
                                        <p>?????????<br />(???)</p>
                                    </div>
                                    <div class='col-sm-4'>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                    </div>
                                </button>
                            </div>
                            ");
                        }
                        break;

                    // - SELECIONA A CARTA QUE SERÁ USADA PARA COMPARAÇÃO
                    case 'carta':
                        if($resultado['status'] == $_SESSION['idus']){
                            $mao = ($_SESSION['idus'] == $resultado['iddesaf'] ? $resultado['maodes'] : $resultado['maoopon']);
                            $idcartas = htmlspecialchars($_GET['n']);
                            $carta = str_pad($idcartas, 3, '0', STR_PAD_LEFT);
                            if(!(strripos($mao, $carta) === FALSE)) {
                                // - Remove a carta da mão
                                $mao = explode(',', $mao);
                                $carta = $idcartas;
                                for($i = 1; isset($mao[$i]); $i++) {
                                    if($mao[$i] == $carta) {
                                        unset($mao[$i]);
                                        $mao[0]--;
                                    }
                                }
                                $mao = implode(',', $mao);
                                if($_SESSION['idus'] == $resultado['iddesaf']) {
                                    mysqli_query($conn, "UPDATE duelo SET maodes = '$mao', cartadesaf = $idcartas WHERE idduelo = '$idduelo'");
                                } else {
                                    mysqli_query($conn, "UPDATE duelo SET maoopon = '$mao', cartaopon = $idcartas WHERE idduelo = '$idduelo'");
                                }

                                // - Exibe a carta na mesa;
                                $consulta = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $idcartas");
                                $resultado = mysqli_fetch_assoc($consulta);
                                    echo ("
                                    <div class='col-sm-12 text-center'>
                                        <button id='carta' value='".$resultado['num_atom']."' class='col-sm-10 btn btn-info' title='Sua carta' style='height:170px'>
                                            <div class='col-sm-8'>
                                                <p><strong>".$resultado['num_atom']."</strong></p>
                                                <h1><strong>".$resultado['simbolo']."</strong></h1>
                                                <p>".$resultado['nome']."<br />".$resultado['mas_atom']."</p>
                                            </div>
                                            <div class='col-sm-4'>
                                            ");
                                $eletrons = explode(',', $resultado['eletrons']);
                                foreach ($eletrons as $e) {
                                    echo ("<li style='list-style-type: none'>".$e."</li>");
                                }
                                echo ("</div>
                                    </button>
                                    </div>");
                            } else {
                                echo ("
                                <button id='carta' type='button' class='col-sm-10 btn btn-default' title='Sua carta' style='height:170px'>
                                    <div class='col-sm-8'>
                                        <p><strong>?</strong></p>
                                        <h1><strong>?</strong></h1>
                                        <p>?????????<br />(???)</p>
                                    </div>
                                    <div class='col-sm-4'>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                        <li style='list-style-type: none'>?</li>
                                    </div>
                                </button>
                                ");
                            }
                        } else {
                            echo 0;
                        }
                        break;

                    // - SELECIONA A PROPRIEDADE QUE SERÁ USADA PARA COMPARAÇÃO
                    case 'propriedade':
                        if($resultado['status'] == $_SESSION['idus']) {
                            $mudastatus = time()+30;
                            $prop = mysqli_real_escape_string($conn, htmlspecialchars($_GET['prop']));
                            mysqli_query($conn, "UPDATE duelo SET propriedade = '$prop' WHERE idduelo = '$idduelo'");
                        } else {
                            echo 0;
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }
            // - Finaliza a conexão com o banco de dados para liberar memória do servidor.
            mysqli_close($conn);
        }
    }
}
?>