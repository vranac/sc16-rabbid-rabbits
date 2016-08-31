RabbitMQ Management CLI
=======================

 - Available at http://localhost:15672/cli/
 
 - You will need python 2.6 or later
 
 - You will can do `chmod a+x rabbitmqadmin.py`

 - Get an overview:
    - ﻿`./rabbitmqadmin.py show overview`

 - Let's create a exchange & queue, publish a message to it, and fetch it
    - Create exchange
        ﻿`./rabbitmqadmin.py declare exchange name="test_http_exchange" type="direct" durable=true`
            
    - List it
        `﻿./rabbitmqadmin.py list exchanges`

    - Create queue 
       `﻿./rabbitmqadmin.py declare queue name="test_http_queue" auto_delete=false durable=true`     
    
    - List it
        `﻿./rabbitmqadmin.py list queues`
            
    - Bind queue to exchange 
        `﻿./rabbitmqadmin.py declare binding source="test_http_exchange" destination="test_http_queue" routing_key="foo"`

    - Publish a message to exchange (POST)
        `./rabbitmqadmin.py publish routing_key="foo" exchange="test_http_exchange" payload="Hello, Web Summer Camp 16 workshop!!" payload_encoding=string`
        
    - Fetch the message (POST - not GET as it can modify(delete) the message)
        `./rabbitmqadmin.py get queue="test_http_queue" count=1 requeue=true`
