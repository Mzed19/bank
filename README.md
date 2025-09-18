# Bank Core

Aplicação backend para gerenciamento de contas bancárias, depósitos e transferências.  
O projeto expõe API's que permitem criar contas, realizar depósitos, transferências e consultar informações da conta logada.

---

## 🚀 Deploy

Para iniciar o projeto pela primeira vez:

```bash
bash initial-up.sh
```

Caso algo dê errado, basta seguir manualmente os comandos contidos no arquivo **`initial-up.sh`**.

---

## 🧭 Tour

- Foram inseridas **10 contas** de teste, **todas com a senha `password`**.  
- É possível criar novas contas através do endpoint **`/accounts`**.  
- Mais informações e exemplos de requisição estão disponíveis na **Collection Postman** incluída no repositório.

---

## 📡 Endpoints Principais

| Endpoint        | Método | Descrição                                                   |
|-----------------|-------|-------------------------------------------------------------|
| **`/accounts/me`** | GET   | Retorna as informações da conta autenticada.               |
| **`/accounts`**    | POST  | Cria uma nova conta.                                       |
| **`/deposits`**    | POST  | Injeta dinheiro no saldo da conta (depósito).              |
| **`/transfer`**    | POST  | Transfere dinheiro entre contas existentes.                |

---

## 🛠️ Tecnologias

- **PHP/Laravel**  
- **Docker**  
- **MySQL**  
- **Nginx**

---

## 📋 Requisitos

- **Docker** e **Docker Compose** instalados  
- **Git** para clonar o repositório

---

## 🧑‍💻 Contribuição

Contribuições são bem-vindas!  
Abra um **issue** ou envie um **pull request** com melhorias ou correções.

---

## 💡 Informações e decisões técnicas
- Utilizei o Observer TransactionObserver como uma abordagem ao Event-Sourcing, servindo para calcular o saldo da conta e visualização de extrato.
- A model Account é uma classe para polimorfismo, caso houvessem mais duas tabelas como PhysicalInformation e JuridicalInformation, elas seriam derivadas ou dependentes da classe Account.
