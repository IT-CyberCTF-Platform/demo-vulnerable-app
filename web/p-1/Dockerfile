FROM php:8.2-apache
ENV TZ=Asia/Bangkok
RUN apt-get update && apt-get install -y git
RUN git clone https://github.com/IT-CyberCTF-Platform/demo-vulnerabe-app.git
RUN mv demo-vulnerabe-app/web/p-1/* /var/www/html/
RUN rm -fr demo-vulnerabe-app/
RUN apt-get remove -y git
EXPOSE 80
