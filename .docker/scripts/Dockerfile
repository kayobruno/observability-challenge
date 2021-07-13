FROM centos:7

RUN yum -y update && \
    yum -y install httpd && \
    yum clean all
    
COPY ./metric-generator.sh /
RUN chmod +x ./metric-generator.sh

ENTRYPOINT ["/metric-generator.sh"]