CREATE DATABASE IF NOT EXISTS aurumdomus;
USE aurumdomus;

-- Create Table Registration
CREATE TABLE Registration (
    RegID INT AUTO_INCREMENT PRIMARY KEY,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    RegType ENUM('Manager', 'Customer', 'Chef', 'Waiter', 'Janitor') NOT NULL,
    dLocation VARCHAR(255),
    accStatus ENUM('Pending', 'Approved', 'Inactive') DEFAULT 'Pending',
    image VARCHAR(255),
    lastAccessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Table Photos
CREATE TABLE Photos (
    PhotoID INT AUTO_INCREMENT PRIMARY KEY,
    RegID INT NOT NULL,
    PhotoPath VARCHAR(255) NOT NULL,
    FOREIGN KEY (RegID) REFERENCES Registration(RegID),
    CONSTRAINT UQ_PhotoPath_Reg UNIQUE (PhotoPath, RegID)
);

-- Create Table Unit
CREATE TABLE Unit (
    UnitID INT AUTO_INCREMENT PRIMARY KEY,
    UnitName ENUM('CBD', 'Suburb') NOT NULL,
    UnitEmail VARCHAR(255) NOT NULL,
    UnitPhone VARCHAR(255) NOT NULL,
    UnitLocation VARCHAR(255) NOT NULL,
    UnitBalance INT NOT NULL,
    Employees INT DEFAULT 0
);

-- Create Table Tables
CREATE TABLE Tables (
    UnitID INT NOT NULL,
    TableID INT AUTO_INCREMENT PRIMARY KEY,
    TableName VARCHAR(255) NOT NULL,
    TableStatus ENUM('Vacant', 'Occupied') NOT NULL,
    TableImage VARCHAR(255),
    FOREIGN KEY (UnitID) REFERENCES Unit(UnitID)
);


-- Create Table Manager
CREATE TABLE Manager (
    ManagerID INT PRIMARY KEY,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    dLocation VARCHAR(255),
    FOREIGN KEY (ManagerID) REFERENCES Registration(RegID)
);

-- Create Table Customer
CREATE TABLE Customer (
    CustomerID INT PRIMARY KEY,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    dLocation VARCHAR(255),
    FOREIGN KEY (CustomerID) REFERENCES Registration(RegID)
);

-- Create Table Chef
CREATE TABLE Chef (
    ChefID INT PRIMARY KEY,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    dLocation VARCHAR(255),
    FOREIGN KEY (ChefID) REFERENCES Registration(RegID)
);

-- Create Table Waiter
CREATE TABLE Waiter (
    WaiterID INT PRIMARY KEY,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    dLocation VARCHAR(255),
    FOREIGN KEY (WaiterID) REFERENCES Registration(RegID)
);

-- Create Table Janitor
CREATE TABLE Janitor (
    JanitorID INT PRIMARY KEY,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    dLocation VARCHAR(255),
    FOREIGN KEY (JanitorID) REFERENCES Registration(RegID)
);

-- Create Table RollCall
CREATE TABLE RollCall (
    RollCallID INT AUTO_INCREMENT PRIMARY KEY,
    RegID INT NOT NULL,
    UnitID INT NOT NULL,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    RollCallStatus ENUM('PRESENT', 'ABSENT') NOT NULL,
    RollCallDate DATE NOT NULL,
    RollCallTime TIME NOT NULL,
    FOREIGN KEY (RegID) REFERENCES Registration(RegID),
    FOREIGN KEY (UnitID) REFERENCES Unit(UnitID)
);

-- Create Table Finance
CREATE TABLE Finance (
    FinanceID INT AUTO_INCREMENT PRIMARY KEY,
    RegID INT NOT NULL,
    Amount DECIMAL(10, 2) NOT NULL,
    Total INT NOT NULL,
    Balance INT NOT NULL,
    TransactionType ENUM('Deposit', 'Withdrawal', 'Salary', 'Supply', 'Payment') NOT NULL,
    TransactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (RegID) REFERENCES Registration(RegID)
);

-- Create Table Salary
CREATE TABLE Salary (
    SalaryID INT AUTO_INCREMENT PRIMARY KEY,
    FinanceID INT NOT NULL,
    RegID INT NOT NULL,
    SalaryAmount DECIMAL(10, 2) NOT NULL,
    SalaryPaid ENUM('YES', 'NO') NOT NULL,
    SalaryReceived ENUM('YES', 'NO') NOT NULL,
    SalaryDate DATE NOT NULL,
    SalaryTime TIME NOT NULL,
    FOREIGN KEY (RegID) REFERENCES Registration(RegID),
    FOREIGN KEY (FinanceID) REFERENCES Finance(FinanceID)
);

-- Create Table Deposit
CREATE TABLE Deposit (
    DepositID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    FinanceID INT NOT NULL,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    DepositAmount DECIMAL(10, 2) NOT NULL,
    DepositDate DATE NOT NULL,
    DepositTime TIME NOT NULL,
    DepositStatus ENUM('Paid', 'Not Paid'),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (FinanceID) REFERENCES Finance(FinanceID)
);

-- Create Table Withdrawal
CREATE TABLE Withdrawal (
    WithdrawalID INT AUTO_INCREMENT PRIMARY KEY,
    ManagerID INT NOT NULL,
    FinanceID INT NOT NULL,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PhoneNo VARCHAR(15) NOT NULL,
    WithdrawalAmount DECIMAL(10, 2) NOT NULL,
    WithdrawalDate DATE NOT NULL,
    WithdrawalTime TIME NOT NULL,
    WithdrawalStatus ENUM('Paid', 'Not Paid'),
    FOREIGN KEY (ManagerID) REFERENCES Manager(ManagerID),
    FOREIGN KEY (FinanceID) REFERENCES Finance(FinanceID)
);

-- Create Table Stock
CREATE TABLE Stock (
    StockID INT AUTO_INCREMENT PRIMARY KEY,
    ItemName VARCHAR(255) NOT NULL,
    ItemDescription VARCHAR(255),
    Quantity INT NOT NULL,
    Cost DECIMAL(10, 2) NOT NULL,
    Total DECIMAL(10, 2) NOT NULL
);

-- Create Table Supply
CREATE TABLE Supply (
    SupplyID INT AUTO_INCREMENT PRIMARY KEY,
    StockID INT NOT NULL,
    Quantity INT NOT NULL,
    SupplyPrice DECIMAL(10, 2) NOT NULL,
    SupplyPayment ENUM('Paid', 'Not Paid'),
    SupplyDate DATE NOT NULL,
    SupplyTime TIME NOT NULL,
    FOREIGN KEY (StockID) REFERENCES Stock(StockID)
);

-- Create Table Dishes
CREATE TABLE Dishes (
    DishID INT AUTO_INCREMENT PRIMARY KEY,
    UnitID INT NOT NULL,
    DishName VARCHAR(255) NOT NULL,
    DishDescription VARCHAR(255),
    DishPrice DECIMAL(10, 2) NOT NULL,
    Available ENUM('YES', 'NO') NOT NULL,
    FOREIGN KEY (UnitID) REFERENCES Unit(UnitID)
);

-- Create Table Orders
CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    UnitID INT NOT NULL,
    CustomerID INT,
    DishID INT,
    DishName VARCHAR(255) NOT NULL,
    DishPrice DECIMAL(10, 2) NOT NULL,
    Plates INT DEFAULT 0,
    OrderPrice DECIMAL(10, 2) NOT NULL,
    OrderDate DATE NOT NULL,
    OrderTime TIME NOT NULL,
    PaymentStatus ENUM('Paid', 'Not Paid') NOT NULL,
    Served ENUM('YES', 'NO') NOT NULL,
    FOREIGN KEY (UnitID) REFERENCES Unit(UnitID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (DishID) REFERENCES Dishes(DishID)
);

-- Create Table Payment
CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY,
    FinanceID INT NOT NULL,
    CustomerID INT NOT NULL,
    OrderID INT NOT NULL,
    Name1 VARCHAR(255) NOT NULL,
    Name2 VARCHAR(255) NOT NULL,
    PaymentType ENUM('Mpesa', 'Cash', 'Card') NOT NULL,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentDate DATE NOT NULL,
    PaymentTime TIME NOT NULL,
    FOREIGN KEY (FinanceID) REFERENCES Finance(FinanceID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID)
);

-- Create Table Feedback
CREATE TABLE Feedback (
    FeedbackID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Comments VARCHAR(255) NOT NULL,
    Response VARCHAR(255),
    Rating INT CHECK (Rating >= 1 AND Rating <= 5),
    FeedbackDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

-- Create Table Bio
CREATE TABLE Bio (
    BioID INT AUTO_INCREMENT PRIMARY KEY,
    UnitID INT,
    Instagram VARCHAR(255) NOT NULL,
    Facebook VARCHAR(255) NOT NULL,
    Twitter VARCHAR(255) NOT NULL,
    UnitPhone VARCHAR(255) NOT NULL,
    UnitLocation VARCHAR(255) NOT NULL,
    AboutUs VARCHAR(255),
    FOREIGN KEY (UnitID) REFERENCES Unit(UnitID)    
);

-- Create Table FAQs
CREATE TABLE FAQs (
    FAQID INT AUTO_INCREMENT PRIMARY KEY,
    Question VARCHAR(255) NOT NULL,
    Answer VARCHAR(255) NOT NULL
);

-- Create Table Reservation
CREATE TABLE Reservation (
    ReservationID INT AUTO_INCREMENT PRIMARY KEY,
    UnitID INT NOT NULL,
    TableID INT NOT NULL,
    CustomerID INT NOT NULL,
    OrderID INT NOT NULL,
    DishID INT NOT NULL,
    DishName VARCHAR(255) NOT NULL,
    Plates INT DEFAULT 0,
    OrderPrice DECIMAL(10, 2) NOT NULL,
    PaymentStatus ENUM('Paid', 'Not Paid') NOT NULL,
    ReservationDate DATE NOT NULL,
    ReservationTime TIME NOT NULL,
    FOREIGN KEY (UnitID) REFERENCES Unit(UnitID),
    FOREIGN KEY (TableID) REFERENCES Tables(TableID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (DishID) REFERENCES Dishes(DishID)
);
