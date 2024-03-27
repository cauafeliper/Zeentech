import smtplib, ssl
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from email.mime.base import MIMEBase
from email import encoders
import sys

def envio_grafico():
    try:
        email_lista = sys.argv[2].split(",")

        message = MIMEMultipart("alternative")
        message["Subject"] = "Gráfico de agendamentos"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = ", ".join(email_lista)

        # Create the plain-text and HTML version of your message
        text = f"""\
        Houveram mudanças nos agendamentos da Pista de Testes. Para ver a tabela de agendamentos dos próximos 30 dias, confira o anexo.
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Houveram mudanças nos agendamentos da Pista de Testes. Para ver a tabela de agendamentos dos próximos 30 dias, confira o anexo.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        filepath = "../anexos/tutorial_grafico.pdf"  # In same directory as script
        filename = "tutorial_grafico.pdf"  # Name of file to be attached

        # Open PDF file in binary mode
        with open(filepath, "rb") as attachment:
            # Add file as application/octet-stream
            # Email client can usually download this automatically as attachment
            part = MIMEBase("application", "octet-stream")
            part.set_payload(attachment.read())

        # Encode file in ASCII characters to send by email    
        encoders.encode_base64(part)

        # Add header as key/value pair to attachment part
        part.add_header(
            "Content-Disposition",
            f"attachment; filename= {filename}",
        )

        # Add attachment to message and convert message to string
        message.attach(part)
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_lista, text
            )
            print('sucesso')
    except Exception as e:
        print(e)

def recuperar_senha():
    try:
        receiver_email = sys.argv[2]
        token = sys.argv[3]

        message = MIMEMultipart("alternative")
        message["Subject"] = "Recuperação da senha"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = receiver_email

        # Create the plain-text and HTML version of your message
        text = f"""\
        Seu token de recuperação de senha é {token}. Esse token vai expirar em 30 minutos!
        Caso a solicitação não tenha sido feita por você, apenas ignore este email.
        
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Seu token de recuperação de senha é {token}. Esse token vai expirar em 30 minutos!<br>Caso a solicitação não tenha sido feita por você, apenas ignore este email.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, receiver_email, text
            )
            print('sucesso')
    except Exception as e:
        print(e)

def cadastro_verificado():
    try:
        receiver_email = sys.argv[2]
        gestorTrue = sys.argv[3]
        admTrue = sys.argv[4]

        message = MIMEMultipart("alternative")
        message["Subject"] = "Email verificado!"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = receiver_email

        # Create the plain-text and HTML version of your message
        text = f"""\
        Seu email foi verificado com sucesso! Segue em anexo o tutorial de como utilizar a página.<br>
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Seu email foi verificado com sucesso! Segue em anexo o tutorial de como utilizar a página.<br>
                <br>
                Atenciosamente,<br>
                Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        filepath = "../anexos/tutorial_usuario.pdf"  # In same directory as script
        filename = "tutorial_usuario.pdf"  # Name of file to be attached

        # Open PDF file in binary mode
        with open(filepath, "rb") as attachment:
            # Add file as application/octet-stream
            # Email client can usually download this automatically as attachment
            part = MIMEBase("application", "octet-stream")
            part.set_payload(attachment.read())

        # Encode file in ASCII characters to send by email    
        encoders.encode_base64(part)

        # Add header as key/value pair to attachment part
        part.add_header(
            "Content-Disposition",
            f"attachment; filename= {filename}",
        )

        # Add attachment to message and convert message to string
        message.attach(part)
        
        if gestorTrue == "True":
            filepath = "../anexos/tutorial_gestor.pdf"  # In same directory as script
            filename = "tutorial_gestor.pdf"  # Name of file to be attached

            # Open PDF file in binary mode
            with open(filepath, "rb") as attachment:
                # Add file as application/octet-stream
                # Email client can usually download this automatically as attachment
                part = MIMEBase("application", "octet-stream")
                part.set_payload(attachment.read())

            # Encode file in ASCII characters to send by email    
            encoders.encode_base64(part)

            # Add header as key/value pair to attachment part
            part.add_header(
                "Content-Disposition",
                f"attachment; filename= {filename}",
            )

            # Add attachment to message and convert message to string
            message.attach(part)
        
        if admTrue == "True":
            filepath = "../anexos/tutorial_administrador.pdf"  # In same directory as script
            filename = "tutorial_administrador.pdf"  # Name of file to be attached

            # Open PDF file in binary mode
            with open(filepath, "rb") as attachment:
                # Add file as application/octet-stream
                # Email client can usually download this automatically as attachment
                part = MIMEBase("application", "octet-stream")
                part.set_payload(attachment.read())

            # Encode file in ASCII characters to send by email    
            encoders.encode_base64(part)

            # Add header as key/value pair to attachment part
            part.add_header(
                "Content-Disposition",
                f"attachment; filename= {filename}",
            )

            # Add attachment to message and convert message to string
            message.attach(part)
        
        text = message.as_string()
        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, receiver_email, text
            )
            print('sucesso')
    except Exception as e:
        print(e)

def verificar_email():
    try:
        receiver_email = sys.argv[2]
        token = sys.argv[3]

        message = MIMEMultipart("alternative")
        message["Subject"] = "Verificação de email"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = receiver_email

        # Create the plain-text and HTML version of your message
        text = f"""\
        Seu token de verificação é {token}. Esse token vai expirar em 30 minutos!
        Caso a solicitação não tenha sido feita por você, apenas ignore este email.
        
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Seu token de verificação é {token}. Esse token vai expirar em 30 minutos!<br>Caso a solicitação não tenha sido feita por você, apenas ignore este email.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, receiver_email, text
            )
            print('sucesso')
    except Exception as e:
        print(e)

def agendamento_aprovado():
    try:
        email_gestor = [email.strip() for email in sys.argv[2].split(",")]
        email_frota = [email.strip() for email in sys.argv[3].split(",")]
        email_usuario = sys.argv[4]
        dataInserida_str = sys.argv[5]
        items = dataInserida_str.split(",") # Split the string by ', ' to get a list of "key: 'value'" strings
        data = {}
        # Loop through the list of "key: 'value'" strings
        for item in items:
            key, value = item.split(": ") # Split the "key: 'value'" string by ': ' to get the key and value
            value = value.strip("'") # Remove the single quotes around the value
            data[key] = value # Add the key and value to the dictionary
        gestor = sys.argv[6]
        
        enviados = 0

        message = MIMEMultipart("alternative")
        message["Subject"] = "Solicitação Aprovada!"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = email_usuario

        # Create the plain-text and HTML version of your message
        text = f"""\
        Sua solicitação de agendamento da área da pista {data['area_pista']} para o dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} foi Aprovada pelo gestor {gestor}!
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Sua solicitação de agendamento da área da pista {data['area_pista']} para o dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} foi Aprovada pelo gestor {gestor}!<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_usuario, text
            )
            enviados += 1
            
        #segundo email
        message = MIMEMultipart("alternative")
        message["Subject"] = "Novo agendamento na Pista de Testes!"
        message["From"] = "SISTEMA RPG"
        message["To"] = ",".join(email_gestor)
        message["Cc"] = ",".join(email_frota)

        # Create the plain-text and HTML version of your message
        text = f"""\
        Um agendamento foi aprovado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} pelo gestor {gestor}!
        Para conferir a tabela de agendamentos dos próximos 30 dias, acesse: {linkGrafico}
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Um agendamento foi aprovado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} pelo gestor {gestor}!<br>Para conferir a tabela de agendamentos dos próximos 30 dias, <a href={linkGrafico}>clique aqui</a>.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_gestor + email_frota, text
            )
            enviados += 1

        if enviados == 2:
            print('sucesso')
        else:
            print('Um dos emails não foi enviado')
    except Exception as e:
        print(e)

def agendamento_reprovado():
    try:
        email_gestor = [email.strip() for email in sys.argv[2].split(",")]
        email_frota = [email.strip() for email in sys.argv[3].split(",")]
        email_usuario = sys.argv[4]
        dataInserida_str = sys.argv[5]
        items = dataInserida_str.split(",") # Split the string by ', ' to get a list of "key: 'value'" strings
        data = {}
        # Loop through the list of "key: 'value'" strings
        for item in items:
            key, value = item.split(": ") # Split the "key: 'value'" string by ': ' to get the key and value
            value = value.strip("'") # Remove the single quotes around the value
            data[key] = value # Add the key and value to the dictionary
        gestor = sys.argv[6]
        statusAnterior = sys.argv[7]

        message = MIMEMultipart("alternative")
        message["Subject"] = "Solicitação Reprovada!"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = email_usuario

        # Create the plain-text and HTML version of your message
        text = f"""\
        Sua solicitação de agendamento da área da pista {data['area_pista']} para o dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} foi reprovada pelo gestor {gestor}!
        Motivo: {data['motivo_reprovacao']}.
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Sua solicitação de agendamento da área da pista {data['area_pista']} para o dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} foi reprovada pelo gestor {gestor}!<br>Motivo: {data['motivo_reprovacao']}.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_usuario, text
            )
        
        if statusAnterior == "Aprovado":
            #segundo email
            message = MIMEMultipart("alternative")
            message["Subject"] = "Agendamento Reprovado!"
            message["From"] = "SISTEMA RPG"
            message["To"] = ",".join(email_gestor)
            message["Cc"] = ",".join(email_frota)

            # Create the plain-text and HTML version of your message
            text = f"""\
            Um agendamento previamente aprovado foi reprovado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} pelo gestor {gestor}!
            Para conferir a tabela de agendamentos dos próximos 30 dias, acesse: {linkGrafico}.
            
            Atenciosamente,
            Equipe Zeentech e Certification Team."""
            html = f"""\
            <html>
            <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
                <p>
                    Um agendamento previamente aprovado foi reprovado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} pelo gestor {gestor}!<br>Para conferir a tabela de agendamentos dos próximos 30 dias, <a href={linkGrafico}>clique aqui</a>.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
                </p>
            </body>
            </html>
            """

            # Turn these into plain/html MIMEText objects
            part1 = MIMEText(text, "plain")
            part2 = MIMEText(html, "html")

            # Add HTML/plain-text parts to MIMEMultipart message
            # The email client will try to render the last part first
            message.attach(part1)
            message.attach(part2)
            
            text = message.as_string()

            # Create secure connection with server and send email
            context = ssl.create_default_context()
            with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
                server.login(sender_email, password)
                server.sendmail(
                    sender_email, email_gestor + email_frota, text
                )
        print('sucesso')
    except Exception as e:
        print(e)

def agendamento_cancelado():
    try:
        email_gestor = [email.strip() for email in sys.argv[2].split(",")]
        email_frota = [email.strip() for email in sys.argv[3].split(",")]
        email_usuario = sys.argv[4]
        dataInserida_str = sys.argv[5]
        items = dataInserida_str.split(",") # Split the string by ', ' to get a list of "key: 'value'" strings
        data = {}
        # Loop through the list of "key: 'value'" strings
        for item in items:
            key, value = item.split(": ") # Split the "key: 'value'" string by ': ' to get the key and value
            value = value.strip("'") # Remove the single quotes around the value
            data[key] = value # Add the key and value to the dictionary

        message = MIMEMultipart("alternative")
        message["Subject"] = "Solicitação Cancelada com sucesso!"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = email_usuario

        # Create the plain-text and HTML version of your message
        text = f"""\
        Você cancelou sua solicitação de agendamento da área da pista {data['area_pista']} para o dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']}!
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Você cancelou sua solicitação de agendamento da área da pista {data['area_pista']} para o dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']}!<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_usuario, text
            )
            
        if data["status"] == "Aprovado":
            #segundo email
            message = MIMEMultipart("alternative")
            message["Subject"] = "Solicitação Cancelada!"
            message["From"] = "SISTEMA RPG"
            message["To"] = ",".join(email_gestor)
            message["Cc"] = ",".join(email_frota)

            # Create the plain-text and HTML version of your message
            text = f"""\
            Um agendamento previamente aprovado foi cancelado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} pelo solicitante {data['solicitante']}!
            Para conferir a tabela de agendamentos dos próximos 30 dias, acesse: {linkGrafico}.
            
            Atenciosamente,
            Equipe Zeentech e Certification Team."""
            html = f"""\
            <html>
            <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
                <p>
                    Um agendamento previamente aprovado foi cancelado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']} pelo solicitante {data['solicitante']}!<br>Para conferir a tabela de agendamentos dos próximos 30 dias, <a href={linkGrafico}>clique aqui</a>.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
                </p>
            </body>
            </html>
            """

            # Turn these into plain/html MIMEText objects
            part1 = MIMEText(text, "plain")
            part2 = MIMEText(html, "html")

            # Add HTML/plain-text parts to MIMEMultipart message
            # The email client will try to render the last part first
            message.attach(part1)
            message.attach(part2)
            
            text = message.as_string()

            # Create secure connection with server and send email
            context = ssl.create_default_context()
            with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
                server.login(sender_email, password)
                server.sendmail(
                    sender_email, email_gestor + email_frota, text
                )
        print('sucesso')
    except Exception as e:
        print(e)

def agendamento():
    try:
        email_gestor = sys.argv[2].split(",")
        email_usuario = sys.argv[3]
        dataInserida_str = sys.argv[4]
        items = dataInserida_str.split(",") # Split the string by ', ' to get a list of "key: 'value'" strings
        dataInserida = {}
        # Loop through the list of "key: 'value'" strings
        for item in items:
            key, value = item.split(": ") # Split the "key: 'value'" string by ': ' to get the key and value
            value = value.strip("'") # Remove the single quotes around the value
            dataInserida[key] = value # Add the key and value to the dictionary
        
        enviados = 0

        message = MIMEMultipart("alternative")
        message["Subject"] = "Solicitação criada com sucesso!"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = email_usuario

        # Create the plain-text and HTML version of your message
        text = f"""\
        Sua solicitação de agendamento da área da pista {dataInserida['area_pista']} para o dia {dataInserida['dia']} de {dataInserida['hora_inicio']} até {dataInserida['hora_fim']} foi criada com sucesso!
        Assim que houver uma resposta de algum Gestor, você receberá um email informando se sua solicitação foi aprovada ou não.
        
        Atenciosamente,
        Equipe Zeentech."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Sua solicitação de agendamento da área da pista {dataInserida['area_pista']} para o dia {dataInserida['dia']} de {dataInserida['hora_inicio']} até {dataInserida['hora_fim']} foi criada com sucesso!<br>Assim que houver uma resposta do Gestor encarregado, você receberá um email dizendo se sua solicitação foi aprovada ou não.<br><br>Atenciosamente,<br>Equipe Zeentech.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_usuario, text
            )
            enviados += 1
            
        #segundo email
        message = MIMEMultipart("alternative")
        message["Subject"] = "Nova solicitação de agendamento!"
        message["From"] = "SISTEMA RPG"
        message["To"] = ", ".join(email_gestor)

        # Create the plain-text and HTML version of your message
        text = f"""\
        Uma nova solicitação para o agendamento da Pista de Teste foi criada pelo colaborador(a) {dataInserida['solicitante']} na área da pista {dataInserida['area_pista']} para o dia {dataInserida['dia']} e horário de {dataInserida['hora_inicio']} até {dataInserida['hora_fim']} com objetivo {dataInserida['objtv']}. Essa nova solicitação aguarda sua resposta!
        
        Atenciosamente,
        Equipe Zeentech."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Uma nova solicitação para o agendamento da Pista de Teste foi criada pelo colaborador(a) {dataInserida['solicitante']} na área da pista {dataInserida['area_pista']} para o dia {dataInserida['dia']} e horário de {dataInserida['hora_inicio']} até {dataInserida['hora_fim']} com objetivo {dataInserida['objtv']}. Essa nova solicitação aguarda sua resposta!<br><br>Atenciosamente,<br>Equipe Zeentech.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_gestor, text
            )
            enviados += 1

        if enviados == 2:
            print('sucesso')
        else:
            print('Um dos emails não foi enviado')
    except Exception as e:
        print(e)

def agendamentoadm():
    try:
        email_gestor = sys.argv[2].split(",")
        email_frota = sys.argv[3].split(",")
        dataInserida_str = sys.argv[4]
        items = dataInserida_str.split(",") # Split the string by ', ' to get a list of "key: 'value'" strings
        data = {}
        # Loop through the list of "key: 'value'" strings
        for item in items:
            key, value = item.split(": ") # Split the "key: 'value'" string by ': ' to get the key and value
            value = value.strip("'") # Remove the single quotes around the value
            data[key] = value # Add the key and value to the dictionary

        message = MIMEMultipart("alternative")
        message["Subject"] = "Novo agendamento na Pista de Teste!"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = ", ".join(email_gestor)
        message["Cc"] = ", ".join(email_frota)

        # Create the plain-text and HTML version of your message
        text = f"""\
        Um agendamento foi aprovado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']}!
        Para conferir a tabela de agendamentos dos próximos 30 dias, acesse: {linkGrafico}
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = f"""\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Um agendamento foi aprovado para a área da pista {data['area_pista']} no dia {data['dia']} de {data['hora_inicio']} até {data['hora_fim']}!<br>Para conferir a tabela de agendamentos dos próximos 30 dias, <a href={linkGrafico}>clique aqui</a>.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, email_gestor + email_frota, text
            )
            print('sucesso')
    except Exception as e:
        print(e)

def addadm():
    try:
        receiver_email = sys.argv[2]

        message = MIMEMultipart("alternative")
        message["Subject"] = "Permissão de Administrador"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = receiver_email

        # Create the plain-text and HTML version of your message
        text = """\
        Você foi adicionado como Administradir na página de agendamento da Pista de Testes! Segue em anexo o tutorial de uso da página para Administradores e Gestores.
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = """\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Você foi adicionado como Administradir na página de agendamento da Pista de Testes! Segue em anexo o tutorial de uso da página para Administradores e Gestores.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)

        filepath = "../anexos/tutorial_administrador.pdf"  # In same directory as script
        filename = "tutorial_administrador.pdf"  # Name of file to be attached

        # Open PDF file in binary mode
        with open(filepath, "rb") as attachment:
            # Add file as application/octet-stream
            # Email client can usually download this automatically as attachment
            part = MIMEBase("application", "octet-stream")
            part.set_payload(attachment.read())

        # Encode file in ASCII characters to send by email    
        encoders.encode_base64(part)

        # Add header as key/value pair to attachment part
        part.add_header(
            "Content-Disposition",
            f"attachment; filename= {filename}",
        )

        # Add attachment to message and convert message to string
        message.attach(part)
        
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, receiver_email, text
            )
            print('sucesso')
    except Exception as e:
        print(e)

def addgestor():
    try:
        receiver_email = sys.argv[2]

        message = MIMEMultipart("alternative")
        message["Subject"] = "Permissão de Gestor"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = receiver_email

        # Create the plain-text and HTML version of your message
        text = """\
        Você foi adicionado como Gestor na página de agendamento da Pista de Testes! Segue em anexo o tutorial de uso da página para Gestores.
        
        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = """\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Você foi adicionado como Gestor na página de agendamento da Pista de Testes! Segue em anexo o tutorial de uso da página para Gestores.<br><br>Atenciosamente,<br>Equipe Zeentech e Certification Team.
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)

        filepath = "../anexos/tutorial_gestor.pdf"  # In same directory as script
        filename = "tutorial_gestor.pdf"  # Name of file to be attached

        # Open PDF file in binary mode
        with open(filepath, "rb") as attachment:
            # Add file as application/octet-stream
            # Email client can usually download this automatically as attachment
            part = MIMEBase("application", "octet-stream")
            part.set_payload(attachment.read())

        # Encode file in ASCII characters to send by email    
        encoders.encode_base64(part)

        # Add header as key/value pair to attachment part
        part.add_header(
            "Content-Disposition",
            f"attachment; filename= {filename}",
        )

        # Add attachment to message and convert message to string
        message.attach(part)
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP_SSL("equipzeentech.com", 465, context=context) as server:
            server.login(sender_email, password)
            server.sendmail(
                sender_email, receiver_email, text
            )
            print('sucesso')
    except Exception as e:
        print(e)

def addcadastro():
    try:
        receiver_email = sys.argv[2]

        message = MIMEMultipart("alternative")
        message["Subject"] = "Tutorial de Cadastro!"
        message["From"] = f"SISTEMA RPG <{sender_email}>"
        message["To"] = receiver_email

        # Create the plain-text and HTML version of your message
        text = """\
        Seu email foi adicionado à lista de cadastros para o site de agendamento da Pista de Testes. Segue em anexo um tutorial de como realizar o cadastro na página.

        Atenciosamente,
        Equipe Zeentech e Certification Team."""
        html = """\
        <html>
        <body style="display:flex;justify-content:center;alignt-items:center;flex-direction:column">
            <p>
                Seu email foi adicionado à lista de cadastros para o site de agendamento da Pista de Testes. Segue em anexo um tutorial de como realizar o cadastro na página.<br>
                <br>
                Atenciosamente,<br>
                Equipe Zeentech e Certification Team.<br>
            </p>
        </body>
        </html>
        """

        # Turn these into plain/html MIMEText objects
        part1 = MIMEText(text, "plain")
        part2 = MIMEText(html, "html")

        # Add HTML/plain-text parts to MIMEMultipart message
        # The email client will try to render the last part first
        message.attach(part1)
        message.attach(part2)

        filepath = "../anexos/tutorial_cadastro.pdf"  # In same directory as script
        filename = "tutorial_cadastro.pdf"  # Name of file to be attached

        # Open PDF file in binary mode
        with open(filepath, "rb") as attachment:
            # Add file as application/octet-stream
            # Email client can usually download this automatically as attachment
            part = MIMEBase("application", "octet-stream")
            part.set_payload(attachment.read())

        # Encode file in ASCII characters to send by email    
        encoders.encode_base64(part)

        # Add header as key/value pair to attachment part
        part.add_header(
            "Content-Disposition",
            f"attachment; filename= {filename}",
        )

        # Add attachment to message and convert message to string
        message.attach(part)
        text = message.as_string()

        # Create secure connection with server and send email
        context = ssl.create_default_context()
        with smtplib.SMTP("equipzeentech.com", 587) as server:
            server.starttls(context=context)
            server.login(sender_email, password)
            server.sendmail(
                sender_email, receiver_email, text
            )
            print('sucesso')
    except Exception as e:
        print(e)


#######################################################################################

# Configurações do email
sender_email = 'admin@equipzeentech.com'
password = 'Z3en7ech'

#links usados
linkGrafico = 'https://www.zeentech.com.br/volkswagen/Agendamento-RPG/grafico/grafico31dias.php'

# Obtém o argumento passado pelo PHP
tipo = sys.argv[1]

if tipo == 'addcadastro':
    addcadastro()
elif tipo == 'addgestor':
    addgestor()
elif tipo == 'addadm':
    addadm()
elif tipo == 'agendamentoadm':
    agendamentoadm()
elif tipo == 'agendamento':
    agendamento()
elif tipo == 'agendamento_aprovado':
    agendamento_aprovado()
elif tipo == 'agendamento_reprovado':
    agendamento_reprovado()
elif tipo == 'agendamento_cancelado':
    agendamento_cancelado()
elif tipo == 'verificar_email':
    verificar_email()
elif tipo == 'cadastro_verificado':
    cadastro_verificado()
elif tipo == 'recuperar_senha':
    recuperar_senha()
elif tipo == 'envio_grafico':
    envio_grafico()