# Sistema de Chamados de TI

Este projeto é um sistema web para abertura, acompanhamento e gestão de chamados técnicos de TI.

## Requisitos

- **XAMPP** (inclui Apache, PHP e MySQL)
- Navegador web moderno

## Instalação e Configuração

1. **Baixe e instale o XAMPP**  
   Faça o download do XAMPP em: [https://www.apachefriends.org/pt_br/index.html](https://www.apachefriends.org/pt_br/index.html)  
   Instale normalmente em seu computador.

2. **Inicie o Apache e o MySQL pelo XAMPP**  
   Abra o painel de controle do XAMPP e clique em "Start" para os serviços Apache e MySQL.

3. **Configure o MySQL para aceitar arquivos grandes**  
   - No painel do XAMPP, clique em "Config" ao lado do MySQL e selecione `my.ini`.
   - Procure pela linha `max_allowed_packet` e altere o valor para:
     ```
     max_allowed_packet=64M
     ```
   - Salve o arquivo e reinicie o MySQL pelo painel do XAMPP.

4. **Obtenha o projeto**  
   - **Clonando via Git:**  
     Abra o terminal, navegue até a pasta `htdocs` do XAMPP e execute:
     ```sh
     git clone https://github.com/Arthu085/chamados-ti
     ```
     Isso criará a pasta `chamados-ti` dentro de `C:\xampp\htdocs\`.
   - **Ou copie os arquivos manualmente:**  
     Baixe o projeto e extraia/copiei todos os arquivos para `C:\xampp\htdocs\chamados-ti`.

5. **Crie o banco de dados**  
   - Acesse o phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Crie um banco de dados chamado `chamados_ti`.
   - Importe o arquivo `db_script.txt` do projeto para criar as tabelas necessárias.

6. **Configure a conexão com o banco de dados**  
   - Edite o arquivo `config/db.php` com as informações do seu MySQL (usuário, senha, host).

7. **Acesse o sistema**  
   No navegador, acesse:  
   [http://localhost/chamados-ti/](http://localhost/chamados-ti/)

## Observações Importantes

- O tamanho máximo permitido para anexos é de **49MB** por arquivo.
- Caso você utilize outra ferramentas de gerenciamento de bancos de dados e os scripts ocasionarem erros, rode uma tabela por vez.

## Tecnologias Utilizadas

- PHP
- MySQL
- Bootstrap 5
- JavaScript
- jQuery

## Funcionalidades Principais

- **Cadastro e login de usuários:**  
  Tela inicial de login e cadastro para acesso ao sistema.

- **Abertura de chamados com anexos:**  
  Na tela "Novo Chamado", o usuário pode descrever o problema, descrever o tipo, adicionar contatos e anexar arquivos (até 49MB cada).

- **Dashboard com estatísticas e listagem:**  
  Após o login, a tela inicial exibe um painel com estatísticas dos chamados (quantidade aberta e finalizada) e uma listagem de todos os chamados do usuário autenticado.

- **Detalhes e histórico do chamado:**  
  Ao clicar em "Detalhes" de um chamado na lista, abre-se a tela de detalhes, onde é possível ver todas as informações, anexos enviados e o histórico de interações.

- **Edição, finalização, reabertura de chamados e exclusão:**  
  Ainda na tela de "Dashboard", o usuário pode editar informações, finalizar o chamado, reabrir caso necessário e excluir.

---
