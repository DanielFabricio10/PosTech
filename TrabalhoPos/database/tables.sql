CREATE TABLE "Client" (
    "cpfcnpj" VARCHAR(14) NOT NULL PRIMARY KEY,
    "name" VARCHAR(60) NOT NULL,
    "lastname" VARCHAR(60) NOT NULL,
    "phone" VARCHAR(30) NOT NULL,
    "email" VARCHAR(60) NOT NULL,
    "birthdate" DATE NOT NULL,
    "idaddress" INT NOT NULL
    FOREIGN KEY ("idaddress") REFERENCES Address("ID")
);

CREATE TABLE "Address" (
    "ID" INT PRIMARY KEY AUTO_INCREMENT,
    "zipcode" VARCHAR(15) NOT NULL,
    "street" VARCHAR(200) NOT NULL,
    "number" VARCHAR(20),
    "neighborhood" VARCHAR(60),
    "city" VARCHAR(60) NOT NULL,
    "uf" CHAR(2) NOT NULL
);
