<?php

function usuarioLogado() {
    return isset($_SESSION['usuario']);
}

function usuarioAtual() {
    return $_SESSION['usuario'];
}

function isAdmin() {
    return $_SESSION['usuario']['perfil'] === 'admin';
}

function isAgente() {
    return $_SESSION['usuario']['perfil'] === 'agente';
}

function isCliente() {
    return $_SESSION['usuario']['perfil'] === 'cliente';
}

function isAgenteOuAdmin() {
    return isAdmin() || isAgente();
}