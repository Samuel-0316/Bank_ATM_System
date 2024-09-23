# ATM Management Database System

## Overview
The ATM Management Database System is designed to efficiently manage banking operations, including customer accounts, ATM transactions, and bank details. This system provides a user-friendly interface for managing bank information, customer records, ATM locations, and transaction history.

## Features
- **Bank Management**: Create, read, update, and delete bank details.
- **ATM Management**: Manage ATM locations linked to specific banks.
- **Customer Management**: Handle customer information, including account details and personal data.
- **Account Management**: Manage customer accounts and their corresponding balances.
- **Card Management**: Securely manage customer ATM cards and associated PINs.
- **Transaction Tracking**: Record and retrieve transaction histories for each card.

## Database Schema

### Tables
1. **Bank**
   - `bank_id` (INT, Primary Key)
   - `bank_name` (VARCHAR(255), NOT NULL)
   - `bank_address` (VARCHAR(255))

2. **ATM**
   - `atm_id` (INT, Primary Key)
   - `atm_name` (VARCHAR(255), NOT NULL)
   - `atm_address` (VARCHAR(255))
   - `bank_id` (INT, Foreign Key referencing Bank)

3. **Customer**
   - `customer_id` (INT, Primary Key)
   - `customer_name` (VARCHAR(255), NOT NULL)
   - `customer_address` (VARCHAR(255))
   - `phone_number` (VARCHAR(20))
   - `pswd` (INT, NOT NULL)

4. **Account**
   - `account_number` (INT, Primary Key)
   - `account_type` (VARCHAR(50), NOT NULL)
   - `balance` (DECIMAL(15, 2))
   - `bank_id` (INT, Foreign Key referencing Bank)
   - `customer_id` (INT, Foreign Key referencing Customer)

5. **Card**
   - `card_number` (VARCHAR(16), Primary Key)
   - `account_number` (INT, Foreign Key referencing Account)
   - `pin` (INT, NOT NULL)

6. **Transaction**
   - `transaction_id` (INT, AUTO_INCREMENT, Primary Key)
   - `status` (VARCHAR(50))
   - `card_number` (VARCHAR(16), Foreign Key referencing Card)
   - `transaction_datetime` (DATETIME)
   - `atm_id` (INT, Foreign Key referencing ATM)
   - `d_amount` (INT, NOT NULL)

## Getting Started
To get started with the ATM Management Database System, clone the repository and set up the database using the provided SQL scripts. 

```bash
git clone https://github.com/Samuel-0316/Bank_ATM_System.git
```

## Installation
1. Import the SQL scripts into your MySQL database.
2. Configure the database connection settings in your application.
3. Run the application.

## Contributing
Contributions are welcome! Please feel free to submit a pull request or raise an issue.
