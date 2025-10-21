FROM alpine:latest
ENV TZ=Asia/Bangkok
RUN apk add --no-cache git php
RUN git clone https://github.com/IT-CyberCTF-Platform/demo-vulnerabe-app.git
RUN mv demo-vulnerabe-app/directory-traversal/php/ lab/
RUN rm -fr demo-vulnerabe-app/
RUN apk del git
RUN echo "secspace{Il5oBelBV3FRs7cp}" > /lab/flag.txt
WORKDIR /lab
EXPOSE 80
CMD ["php", "-S","0.0.0.0:80"] 
