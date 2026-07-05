# MARCOS ORLANDO 04/10/2024

## -- Tabela de Usuário cartão linktree

```
CREATE TABLE `trv_card_user` (
  `carduser_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carduser_thumb` varchar(255) DEFAULT NULL,
  `carduser_name` varchar(255) DEFAULT NULL,
  `carduser_lastname` varchar(255) DEFAULT NULL,
  `carduser_url` varchar(255) DEFAULT NULL,
  `carduser_email` varchar(255) DEFAULT NULL,
  `carduser_phone` varchar(45) DEFAULT NULL,
  `carduser_cargo` varchar(155) DEFAULT NULL,
  `carduser_state` char(2) DEFAULT NULL,
  `carduser_city` varchar(255) DEFAULT NULL,
  `carduser_created` timestamp NULL DEFAULT current_timestamp(),
  `carduser_updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `carduser_status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`carduser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```
