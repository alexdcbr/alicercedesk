# 🚀 AlicerceDesk

Sistema de gerenciamento de chamados inspirado em plataformas como Zendesk.  
Desenvolvido em **PHP puro + MySQL**, com foco em aprendizado progressivo e arquitetura escalável.

---

## 🧩 Tecnologias

- 🟢 PHP
- 🔵 MySQL
- 🎨 CSS (layout estilo SaaS)
- ⚡ JavaScript (AJAX polling)
- 📊 Chart.js

---

## 📌 Funcionalidades

- Cadastro e autenticação de usuários  
- Abertura de chamados  
- Chat em tempo quase real (polling AJAX)  
- Upload de anexos  
- Preview de imagens  
- Controle de permissões (cliente / agente / admin)  
- Dashboard com métricas  

---

## 📊 Dashboard

- KPIs de chamados  
- Gráfico de status (rosca)  
- Gráfico de evolução (linha)  
- SLA (tempo médio de resolução)  
- FRT (tempo de primeira resposta)  

---

## 🎨 Interface

- Layout moderno estilo SaaS  
- Sidebar com navegação ativa  
- Ícones (Bootstrap Icons)  
- Chat estilo WhatsApp  
- Modal para visualização de imagens  

---

## 🔐 Regras de Acesso

- 👤 **Cliente**
  - Visualiza apenas seus chamados  
  - Sem acesso a métricas e gráficos  

- 🧑‍💼 **Agente/Admin**
  - Visualiza todos os chamados  
  - Acesso ao dashboard completo  

---

## 🧠 Arquitetura

- Estrutura com **partials**
  - `header.php`
  - `sidebar.php`
  - `footer.php`

- Separação de responsabilidades:
  - Layout
  - Lógica
  - Estilo (CSS)

- API interna com AJAX para chat

---

## 🗄️ Banco de Dados

Principais tabelas:

- `usuarios`
- `chamados`
- `mensagens_chamado`
- `anexos`

Campos importantes:

- `resolvido_em` → SLA  
- `primeira_resposta_em` → FRT  

---

## 📈 Métricas Implementadas

- ⏱️ **SLA**
  - Tempo médio de resolução (em horas)

- ⚡ **FRT (First Response Time)**
  - Tempo até a primeira resposta (em minutos)

---

## 🛠️ Histórico de Evolução

### Fase 1
- CRUD de chamados

### Fase 2
- Sistema de login e permissões

### Fase 3
- Chat interno

### Fase 4
- Upload e preview de arquivos

### Fase 5
- Interface moderna (CSS)

### Fase 6
- Chat em tempo real (polling AJAX)

### Fase 7
- Dashboard com métricas

### Fase 8
- SLA (tempo de resolução)

### Fase 9
- FRT (tempo de primeira resposta)

### Fase 10
- Refatoração com layout reutilizável (partials)

### Fase 11
- UI estilo SaaS (Zendesk-like)

---

## 🚀 Próximos Passos

- 🔥 WebSocket real (Node.js)
- 🔔 Notificações em tempo real
- 👤 Ranking de atendentes
- 🚨 SLA com alertas
- 📊 Relatórios avançados
- 📱 Layout responsivo (mobile)
- 🌙 Tema escuro

---

## 🧪 Como Executar o Projeto

1. Instale o **Laragon** ou outro ambiente com PHP + MySQL  
2. Clone o projeto:
   ```bash
   git clone http://localhost/alicercedesk